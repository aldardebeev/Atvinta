<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes_user extends Model
{
    use HasFactory;

    protected $table = 'notes_user';

    protected $fillable = [
        'user_id',
        'notes_id'
    ];
}
