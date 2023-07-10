<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/signup', [UserController::class, 'signup']);


Route::get('/notes', [NoteController::class, 'index']);
Route::post('/note/{slug}', [NoteController::class, 'decrypt']);
Route::post('/note-create', [NoteController::class, 'createNote']);




// routes/api.php
Route::group(['middleware' => ['auth:web,api']], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/user-notes', [NoteController::class, 'showUserNotes']);
});
