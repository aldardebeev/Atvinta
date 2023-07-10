<?php

namespace App\Service;

use App\Models\Note;
use App\Repository\NotesRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NoteService
{
    protected $noteRepository;

    public function __construct(NotesRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function getUserNotesData($user, $limit)
    {
        $notes = $this->noteRepository->getUserNotes($user, $limit);

        $notesData = $notes->map(function ($note) {
            $words = explode(' ', $note->text);
            $shortText = implode(' ', array_slice($words, 0, 15));

            return [
                'slug' => $note->slug,
                'title' => $note->title,
                'noteText' => $shortText,
                'access_type' => $note->access_type,
                'text_type' => $note->text_type,
            ];
        });

        return $notesData;
    }

    public function getUserNotes($limit)
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return $this->getUserNotesData($user, $limit);
    }

    public function getPublicNotesWithUsers(int $limit)
    {
        $currentDateTime = Carbon::now();

        $notes = Note::where('access_type', 'public')
            ->where(function ($query) use ($currentDateTime) {
                $query->where('expiration_date', '>=', $currentDateTime)
                    ->orWhereNull('expiration_date');
            })
            ->orderByDesc('created_at')
            ->take($limit)
            ->with('users')
            ->get();

        $notes = $notes->map(function ($note) {
            $words = explode(' ', $note->text);
            $shortText = implode(' ', array_slice($words, 0, 15));

            $note->text = $shortText;
            $note->user_name = $note->users->first() ? $note->users->first()->name : null;

            return $note;
        });

        return $notes;
    }

    public function getExpirationDate(?string $expiration_date_value): ?Carbon
    {
        if (!$expiration_date_value) {
            return null;
        }

        return match ($expiration_date_value) {
            '10_min' => Carbon::now()->addMinutes(10),
            '1_hour' => Carbon::now()->addHour(),
            '3_hour' => Carbon::now()->subHours(3),
            '1_day' => Carbon::now()->addDay(),
            '1_week' => Carbon::now()->addWeek(),
            '1_month' => Carbon::now()->addMonth(),
            default => null,
        };
    }


}
