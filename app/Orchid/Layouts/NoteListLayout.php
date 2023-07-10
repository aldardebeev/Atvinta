<?php

namespace App\Orchid\Layouts;

use App\Models\Note;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
class NoteListLayout extends Table
{

    protected $target = 'note';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(function (Note $note) {
                    return Link::make($note->title)
                        ->route('platform.post.edit', $post);
                }),

        ];
    }
}
