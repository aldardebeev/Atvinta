<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteCreateRequest;
use App\Models\Note;
use App\Models\User;
use App\Repository\NotesRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class NoteController extends Controller
{

    public function index(): View|ViewFactory
    {
        $data = Note::latest()->get();
        $notes = [];

        foreach ($data as $note) {
            if ($note->access_type === 'public') {
                $slug = $note->slug;
                $decryptedNote = Crypt::decryptString($note->text);
                $notes[] = [
                    'slug' => $slug,
                    'title' => $note->title,
                    'text' => $decryptedNote
                ];
            }
        }

        $notes = array_slice($notes, 0, 10);

        return view('home', [
            'notes' => $notes
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
            $this->getAccessType($request->getAccessType()),
            $request->getPassword() ? Hash::make($request->getPassword()) : null,
            $this->getExpirationDate($request->getExpirationDate())
        );

        $user = Auth::user();
        if($user){
            $user->notes()->attach($note);
            if ($note->access_type === 'unlisted'){
                return view('note.show-link', [
                    'hide_footer' => true,
                    'note_url'    => route('note.decrypt', ['slug' => $note->slug]),
                ]);
            }
            else{
                return $this->showUserNotes();
            }
        }
        else {
            if ($note->access_type === 'unlisted'){
                return view('note.show-link', [
                    'hide_footer' => true,
                    'note_url'    => route('note.decrypt', ['slug' => $note->slug]),
                ]);
            }
            elseif ($note->access_type === 'private'){
                return view('signup');
            }
            else{
                return $this->index();
            }
        }
    }

    public function decrypt(string $slug, NotesRepository $notes_repository): ViewFactory|View|Application|RedirectResponse
    {

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

        $note_title = $note->title;

        return view('note.show', [
            'hide_footer' => true,
            'note_text'   => $note_text,
            'note_title'  => $note_title
        ]);
    }


    public function showUserNotes()
    {
        $user = Auth::user();
        if (!$user) {
            return route('signup');
        }

        $notes = $user->notes()->latest()->take(10)->get();
        $noteTitle = $notes->pluck('title')->reverse();
        $notesAccess = $notes->pluck('access_type')->reverse();
        $slug = $notes->pluck('slug')->reverse();
        $notesText = [];

        foreach ($notes as $note) {
            $decryptedNote = Crypt::decryptString($note->text);
            $notesText[] = $decryptedNote;
        }

        $name = $user->name;


        return view('myNotes', [
            'slug' => $slug,
            'title' => $noteTitle,
            'name' => $name,
            'notes' => $notesText,
            'access_type' => $notesAccess
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
    }

    private function getAccessType(?string $Access_date_value)
    {
        if (! $Access_date_value) {
            return null;
        }

        return match ($Access_date_value) {
            'public'    => 'public',
            'unlisted'  => 'unlisted',
            'private'   => 'private',
            default     => null,
        };


    }
}
