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
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(): View|ViewFactory
    {
        $currentDateTime = Carbon::now();

        $notes = Note::where('access_type', 'public')
            ->where(function ($query) use ($currentDateTime) {
                $query->where('expiration_date', '>=', $currentDateTime)
                    ->orWhereNull('expiration_date');
            })
            ->orderByDesc('created_at')
            ->take(10)
            ->with('users')
            ->get();

        $notes = $notes->map(function ($note) {
            $words = explode(' ', $note->text);
            $shortText = implode(' ', array_slice($words, 0, 15));

            $note->text = $shortText;
            $note->user_name = $note->users->first() ? $note->users->first()->name : null;
            return $note;
        });

        return view('home', [
            'notes' => $notes,
        ]);
    }


    public function showCreatePage(): View|ViewFactory
    {
        return view('note.new');
    }

    public function createNote(NoteCreateRequest $request, NotesRepository $notes_repository): Application|View|ViewFactory
    {
        $note = $notes_repository->create(
            $request->getText(),
            $request->getTitle(),
            $request->getAccessType(),
            $request->getTextType(),
            $this->getExpirationDate($request->getExpirationDate())
        );

        $user = Auth::user();

        if ($note->access_type === 'unlisted') {
            return view('note.show-link', [
                'hide_footer' => true,
                'note_url' => route('note.decrypt', ['slug' => $note->slug]),
            ]);
        } elseif ($user) {
            $user->notes()->attach($note);
            return $this->showUserNotes();
        } elseif ($note->access_type === 'private') {
            return view('signup');
        } else {
            return $this->index();
        }
    }

    public function showNote(string $slug, NotesRepository $notes_repository): ViewFactory|View|Application|RedirectResponse
    {
        $note = $notes_repository->findBySlug($slug);
        if (! $note instanceof Note) {
            return back()->withErrors(['404' => 'The note does not exist, has already been read, or has expired']);
        }

        return view('note.show', [
            'note_text' => $note->text,
            'note_title' => $note->title,
            'text_type' => $note->text_type,
            'id' => $note->id,
        ]);
    }


    public function showUserNotes()
    {
        $user = Auth::user();
        if (!$user) {
            return route('signup');
        }
        $currentDateTime = Carbon::now();
        $notes = $user->notes()->latest()->take(10)->where(function ($query) use ($currentDateTime) {
            $query->where('expiration_date', '>=', $currentDateTime)
                ->orWhereNull('expiration_date');  })
            ->get();
        $notesData = [];

        foreach ($notes as $note) {
            $words = explode(' ', $note->text);
            $shortText = implode(' ', array_slice($words, 0, 15));

            $notesData[] = [
                'slug' => $note->slug,
                'title' => $note->title,
                'noteText' => $shortText,
                'access_type' => $note->access_type,
                'text_type' => $note->text_type
            ];
        }
        return view('myNotes', compact('notesData'));
    }

    private function getExpirationDate(?string $expiration_date_value): ?Carbon
    {
        if (! $expiration_date_value) {
            return null;
        }

        return match ($expiration_date_value) {
            '10_min'  => Carbon::now()->addMinutes(1),
            '1_hour'  => Carbon::now()->addHour(),
            '3_hour'  => Carbon::now()->subHours( 3),
            '1_day'   => Carbon::now()->addDay(),
            '1_week'  => Carbon::now()->addWeek(),
            '1_month' => Carbon::now()->addMonth(),
            default   => null,
        };
    }



}
