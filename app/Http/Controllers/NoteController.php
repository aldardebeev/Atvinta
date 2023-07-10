<?php
namespace App\Http\Controllers;

use App\Http\Requests\NoteCreateRequest;
use App\Models\Note;
use App\Repository\NotesRepository;
use App\Service\NoteService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    private $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function index(): View|ViewFactory
    {
        $notes = $this->noteService->getPublicNotesWithUsers(10);

        return view('home', [
            'notes' => $notes,
        ]);
    }

    /**
     * Show the create note page.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showCreatePage(): View|ViewFactory
    {
        return view('note.new');
    }

    /**
     * Create a new note.
     *
     * @param  \App\Http\Requests\NoteCreateRequest  $request
     * @param  \App\Repository\NotesRepository  $notes_repository
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createNote(NoteCreateRequest $request, NotesRepository $notes_repository): Application|View|ViewFactory
    {
        $note = $notes_repository->create(
            $request->getText(),
            $request->getTitle(),
            $request->getAccessType(),
            $request->getTextType(),
            $this->noteService->getExpirationDate($request->getExpirationDate())
        );

        $user = Auth::user();

        if ($note->access_type === 'unlisted') {
            return view('note.show-link', [
                'note_url' => route('show.note', ['slug' => $note->slug]),
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



    /**
     * Show a specific note.
     *
     * @param  string  $slug
     * @param  \App\Repository\NotesRepository  $notes_repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Show the notes of the currently authenticated user.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse
     */
    public function showUserNotes()
    {
        $notesData = $this->noteService->getUserNotes(10);

        if (!$notesData) {
            return redirect()->route('signup');
        }

        return view('myNotes', compact('notesData'));
    }

    /**
     * Get the expiration date based on the given value.
     *
     * @param  string|null  $expiration_date_value
     * @return \Carbon\Carbon|null
     */

}
