<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int         $id
 * @property string      $title
 * @property string      $text
 * @property string      $access_type
 * @property string      $slug
 * @property string|null $password
 * @property Carbon|null $expiration_date
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
class Note extends EloquentModel
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'slug', 'access_type', 'expiration_date', 'password'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notes_user');
    }

}
