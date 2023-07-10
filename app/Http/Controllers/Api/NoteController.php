<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\NoteCreateRequest;
use App\Models\Note;
use App\Repository\NotesRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rector\Enum\JsonConstant;

class NoteController
{
    public function index()
    {
        $data = Note::latest()->get();
        $notes = [];

        foreach ($data as $note) {
            if ($note->access_type === 'public') {

                $slug = $note->slug;
                $text_type = $note->text_type;



                $words = explode(' ', $note->text);
                $shortText = implode(' ', array_slice($words, 0, 15));

                $noteData = [
                    'slug' => $slug,
                    'title' => $note->title,
                    'text' => $shortText,
                    'text_type' => $text_type,

                ];

                $user = $note->users()->first();
                if ($user) {
                    $noteData['user_name'] = $user->name;
                }

                $notes[] = $noteData;
            }
        }

        $notes = array_slice($notes, 0, 10);

        return response()->json( [
            'notes' => $notes,
        ]);
    }

    public function showUserNotes()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notes = $user->notes()->latest()->take(10)->get();
        $name = $user->name;

        return response()->json([
            'name' => $name,
            'des' => $notes
        ]);
    }

    public function createNote(NoteCreateRequest $request, NotesRepository $notes_repository)
    {
        $note = $notes_repository->create(
            $request->getText(),
            $request->getTitle(),
            $this->getAccessType($request->getAccessType()),
            $this->getTextType($request->getTextType()),
            $request->getPassword() ? Hash::make($request->getPassword()) : null,
            $this->getExpirationDate($request->getExpirationDate())
        );

        $user = Auth::user();
        if ($user) {
            $user->notes()->attach($note);
            if ($note->access_type === 'unlisted') {
                return response()->json([
                    'note_url' => route('note.decrypt', ['slug' => $note->slug])
                ]);
            } else {
                return $this->showUserNotes();
            }
        } else {
            if ($note->access_type === 'unlisted') {
                return response()->json([
                    'note_url' => route('note.decrypt', ['slug' => $note->slug])
                ]);
            } elseif ($note->access_type === 'private') {
                return response()->json(['error' => 'User authentication required'], 401);
            } else {
                return $this->index();
            }
        }
    }

    public function decrypt(string $slug, NotesRepository $notes_repository)
    {
        $requestData = request()->validate([
            'decrypt_password' => 'string|max:100',
        ]);

        $note = $notes_repository->findBySlug($slug);
        if (! $note instanceof Note) {
            return response()->json(['error' => 'The note does not exist, has already been read, or has expired'], 404);
        }

        if ($note->password !== null && ! Hash::check($requestData['decrypt_password'], $note->password)) {
            return response()->json(['error' => 'Password incorrect'], 403);
        }

        $note_title = $note->title;
        $text_type = $note->text_type;

        return response()->json([
            'note_text' => $note->text,
            'note_title' => $note_title,
            'text_type' => $text_type,
            'id' => $note->id,
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
    private function getTextType(?string $Text_date_value)
    {
        if (! $Text_date_value) {
            return null;
        }

        return match ($Text_date_value) {
            'text'      => 'text',
            'php'       => 'php',
            'html'      => 'html',
            default     => null,
        };
    }
}
