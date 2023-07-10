<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Comment extends Model
{
    use AsSource;
    protected $fillable = ['text', 'author', 'note_id'];

    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }

}
