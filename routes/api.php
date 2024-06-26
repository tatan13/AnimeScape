<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/anime', [App\Http\Controllers\AnimeController::class, 'showAllAnimeList']);

Route::get('/cast/{cast_id}', [App\Http\Controllers\CastController::class, 'getCastNameById']);

Route::get('/creater/{creater_id}', [App\Http\Controllers\CreaterController::class, 'getCreaterNameById']);

Route::get('/tag/name/{tag_name}', [App\Http\Controllers\TagController::class, 'getTagIdByNameForApi']);

Route::get('/tag/id/{tag_id}', [App\Http\Controllers\TagController::class, 'getTagNameByIdForApi']);

Route::get('/anime/title/{anime_title}', [App\Http\Controllers\AnimeController::class, 'getAnimeIdByTitleForApi']);

Route::get('/anime/id/{anime_id}', [App\Http\Controllers\AnimeController::class, 'getAnimeTitleByIdForApi']);
