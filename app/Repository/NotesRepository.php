<?php

namespace App\Repository;

use App\Models\Note;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Support\Facades\Crypt;

class NotesRepository
{
    public function __construct(private readonly Hashids $hashids)
    {
    }

    public function findBySlug(string $slug): ?Note
    {
        $note = Note::where('slug', $slug)->first();

        return $note instanceof Note ? $note : null;
    }


    public function create(string $text, string $title, ?string $password, ?Carbon $expiration_date): Note
    {
        $note                  = new Note();
        $note->text            = Crypt::encryptString($text);
        $note->title           = $title;
        $note->expiration_date = $expiration_date;
        $note->password        = $password;
        $note->slug            = time() . '-' . random_int(0, mt_getrandmax());
        $note->save();

        $note->refresh();

        $note->slug = $this->hashids->encode($note->id);
        $note->save();

        return $note;
    }
}
