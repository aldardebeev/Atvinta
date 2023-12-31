<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

    Route::name('user.')->group(function (){

    Route::get('/signin',function (){
        return view('signin');
    })->name('signin');

    Route::get('/signup', function (){
        return view('signup');
    })->name('signup');

    Route::post('/signup', [UserController::class, 'signup'])->name('signup');
    Route::post('/signin', [UserController::class, 'signin'])->name('signin');

    Route::get('/logout', function (){
        Auth::logout();
        return redirect('home');
    })->name('logout');

        Route::get('/vk/auth', [SocialController::class, 'index'])->name('vk');
        Route::get('/vk/auth/callback', [SocialController::class, 'callback']);
    });


Route::post('/note/comment/{id}', [CommentController::class, 'create'])->name('comment');

Route::get('/home', [NoteController::class, 'index'])->name('home');

Route::get('/new-note', [NoteController::class, 'showCreatePage'])->name('page.note.new');
Route::get('/note/{slug}', [NoteController::class, 'showNote'])->name('show.note');
Route::post('/notes', [NoteController::class,'createNote'])->name('note.create');
Route::get('/my-notes', [NoteController::class,'showUserNotes'])->middleware('auth')->name('myNotes');


Route::post('/note/{slug}', [NoteController::class, 'decrypt'])->name('note.showNote');









