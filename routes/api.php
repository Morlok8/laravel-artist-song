<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ArtistController;
use App\Http\Controllers\api\SongController;
use App\Http\Controllers\api\AlbumController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('artist')->group(function () {
    Route::get('/artists', [ArtistController::class, 'index']);
    Route::get('/artists/{id}', [ArtistController::class, 'show']);
    Route::post('/artist', [ArtistController::class, 'store']);
    Route::put('/artist/{id}', [ArtistController::class, 'update']);
    Route::delete('/artist/{id}', [ArtistController::class, 'destroy']);
});

Route::name('song')->group(function () {
    Route::get('/songs', [SongController::class, 'index']);
    Route::get('/song/{id}', [SongController::class, 'show']);
    Route::post('/song', [SongController::class, 'store']);
    Route::put('/song/{id}', [SongController::class, 'update']);
    Route::delete('/song/{id}', [SongController::class, 'destroy']);
});

Route::name('album')->group(function () {
    Route::get('/albums', [AlbumController::class, 'index']);
    Route::get('/album/{id}', [AlbumController::class, 'show']);

    Route::get('/album/{id}/songs/', [AlbumController::class, 'get_songs']);
    Route::post('/album/{id}/song/', [AlbumController::class, 'add_song']);
    Route::delete('/album/{id}/song/{song_id}', [AlbumController::class, 'remove_song']);

    Route::post('/album', [AlbumController::class, 'store']);
    Route::put('/album/{id}', [AlbumController::class, 'update']);
    Route::delete('/album/{id}', [AlbumController::class, 'destroy']);
});

