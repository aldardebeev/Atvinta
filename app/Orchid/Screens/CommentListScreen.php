<?php

namespace App\Orchid\Screens;

use App\Models\Comment;


use App\Models\Note;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class CommentListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $name = 'Жалобы';

    public $description = 'Список жалоб на пасты';

    public function query(): iterable
    {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            $user = User::find($comment->author);
            $note = Note::find($comment->note_id);
                $comment->author = $user->name;

        }

        return [
            'comments' => $comments
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Жалобы';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('comments', [
                TD::make('note_id', 'Паста')
                    ->render(function (Comment $comment) {
                        return Link::make($comment->note_id)
                            ->href(route('platform.note.show', Note::findOrFail($comment->note_id)));
                    }),
                TD::make('author', 'Пожаловался'),
                TD::make('text', 'Содержание')
            ])
        ];
    }
}
