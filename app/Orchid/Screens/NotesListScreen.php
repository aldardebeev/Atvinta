<?php

namespace App\Orchid\Screens;

use App\Models\Note;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;


class NotesListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */


    public $name = 'Пасты';

    public $description = 'Список паст';

    public function query(): iterable
    {
        return [
            'notes' => Note::all()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Пасты';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */


    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('notes', [
                TD::make('title', ' Название')->width('200'),
                TD::make('access_type', 'Ограничение доступа')->width('50'),
                TD::make('text_type', 'Тип текста')->width('50'),
                TD::make('text', 'Содержание')->width('600'),
                TD::make('', 'Действие')
                    ->width('100')
                    ->render(function (Note $note) {
                        return Button::make('Удалить')
                            ->icon('trash')
                            ->confirm('Вы уверены, что хотите удалить эту запись?')
                            ->method('remove')
                            ->parameters(['note' => $note->id]);
                    }),
            ])
        ];
    }

    public function remove(Note $note)
    {
        $note->delete();

        Alert::info('Вы успешно удалили запись.');

        return redirect()->route('platform.notes');
    }
}
