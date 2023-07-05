<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteCreateRequest;
use App\Models\Note;
use App\Repository\NotesRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class NoteController extends Controller
{

    public function index(): View|ViewFactory
    {
        return view('home', [

        ]);
    }

    public function showCreatePage(): View|ViewFactory
    {
        return view('note.new', [
            'hide_footer' => true,
        ]);
    }

    public function openLink(string $slug, NotesRepository $notes_repository): View|ViewFactory
    {
        $note = $notes_repository->findBySlug($slug);

        if ($note instanceof Note && $note->expiration_date !== null && $note->expiration_date < now()) {
            $note->delete();
            $note = null;
        }

        return view('note.show', [
            'hide_footer' => true,
            'note'        => $note,
        ]);
    }

    public function createNote(NoteCreateRequest $request, NotesRepository $notes_repository): Application|View|ViewFactory
    {
        $note = $notes_repository->create(
            $request->getText(),
            $request->getTitle(),
            $request->getPassword() ? Hash::make($request->getPassword()) : null,
            $this->getExpirationDate($request->getExpirationDate())
        );

        return view('note.show-link', [
            'hide_footer' => true,
            'note_url'    => route('note.decrypt', ['slug' => $note->slug]),
        ]);
    }

    public function decrypt(
        string $slug,
        NotesRepository $notes_repository
    ): ViewFactory|View|Application|RedirectResponse {


        request()->validate([
            'decrypt_password' => 'string|max:100',
        ]);

        $note = $notes_repository->findBySlug($slug);
        if (! $note instanceof Note) {
            return back()->withErrors(['404' => 'The note does not exist, has already been read, or has expired']);
        }

        if ($note->password !== null && ! Hash::check(request()->decrypt_password, $note->password)) {
            return back()->withErrors(['bad_password' => 'Password incorrect']);
        }

        $note_text = Crypt::decryptString($note->text);

//        $note->delete(); удаление заметки

        $note_title = $note->title;

        return view('note.show', [
            'hide_footer' => true,
            'note_text'   => $note_text,
            'note_title'  => $note_title
        ]);
    }

    private function getExpirationDate(?string $expiration_date_value): ?Carbon
    {
        if (! $expiration_date_value) {
            return null;
        }

        return match ($expiration_date_value) {
            '10_min'  => Carbon::now()->addMinutes(10),
            '1_hour'  => Carbon::now()->addHour(),
            '3_hour'  => Carbon::now()->subHours( 3),
            '1_day'   => Carbon::now()->addDay(),
            '1_week'  => Carbon::now()->addWeek(),
            '1_month' => Carbon::now()->addMonth(),
            default   => null,
        };
    } private function getAccessRestriction(?string $Access_date_value): ?Carbon
    {
        if (! $Access_date_value) {
            return null;
        }

        return match ($Access_date_value) {
            'public'  => Carbon::now()->addMinutes(10),
            'unlisted'  => Carbon::now()->addHour(),
            'private'  => Carbon::now()->subHours( 3),
            default   => null,
        };
    }
}
