<?php

namespace App\Repository;

use App\Models\Note;

class NoteRepository
{
    public function create(array $data): Note
    {
        return Note::create($data);
    }

    public function findBySlug(string $slug): ?Note
    {
        return Note::where('slug', $slug)->first();
    }

    public function getUserNotes(int $userId, int $limit = 10): array
    {
        return Note::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->latest()
            ->take($limit)
            ->with('users')
            ->get()
            ->map(function ($note) {
                $words = explode(' ', $note->text);
                $shortText = implode(' ', array_slice($words, 0, 15));

                $note->text = $shortText;
                $note->user_name = $note->users->first() ? $note->users->first()->name : null;
                return $note;
            })
            ->toArray();
    }
}
