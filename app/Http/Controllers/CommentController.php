<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Create a new comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, $id): RedirectResponse
    {
        if (!Auth::user()) {
            return redirect()->back()->with('error', 'Нужно войти в профиль');
        } else {
            $validatedData = $request->validate([
                'text' => 'required',
            ]);

            $comment = new Comment();
            $comment->text = $validatedData['text'];
            $comment->author = Auth::user()->id;
            $comment->note_id = $id;
            $comment->save();

            return redirect()->back()->with('success', 'Жалоба отправлена');
        }
    }
}
