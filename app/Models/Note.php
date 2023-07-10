<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * @property int         $id
 * @property string      $title
 * @property string      $text
 * @property string      $access_type
 * @property string      $text_type
 * @property string      $slug
 * @property Carbon|null $expiration_date
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
class Note extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = ['title', 'text', 'slug', 'access_type', 'text_type', 'expiration_date', ];



    public function users()
    {
        return $this->belongsToMany(User::class, 'notes_user');
    }



}
