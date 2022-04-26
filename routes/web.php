<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('/anime/{id}', [App\Http\Controllers\AnimeController::class, 'show'])->name('anime');

Route::get('/cast/{id}', [App\Http\Controllers\CastController::class, 'show'])->name('cast');

Route::get('/user_information/{uid}', [App\Http\Controllers\UserController::class, 'show'])->name('user');

Route::get('/user_information/{uid}/will_watch_anime_list', [App\Http\Controllers\UserController::class, 'showWillWatchAnimeList'])->name('user.will_watch_anime_list');

Route::get('/user_information/{uid}/like_user_list', [App\Http\Controllers\UserController::class, 'showLikeUserList'])->name('user.like_user_list');

Route::get('/user_information/{uid}/liked_user_list', [App\Http\Controllers\UserController::class, 'showLikedUserList'])->name('user.liked_user_list');

Route::get('/user_information/{uid}/like_cast_list', [App\Http\Controllers\UserController::class, 'showLikeCastList'])->name('user.like_cast_list');

Route::get('/user_information/{uid}/statistics', [App\Http\Controllers\UserController::class, 'statistics'])->name('user.statistics');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'post'])->name('contact.post');

Route::get('/anime_statistics', [App\Http\Controllers\StatisticsController::class, 'show'])->name('anime_statistics');

Route::get('/modify/anime/{id}', [App\Http\Controllers\ModifyController::class, 'modifyAnimeShow'])->name('modify.anime.show');

Route::post('/modify/anime/{id}', [App\Http\Controllers\ModifyController::class, 'modifyAnimePost'])->name('modify.anime.post');

Route::get('/update_log', [App\Http\Controllers\IndexController::class, 'updateLog'])->name('update_log');

Route::get('/modify/occupation/{id}', [App\Http\Controllers\ModifyController::class, 'modifyOccupationShow'])->name('modify.occupation.show');

Route::post('/modify/occupation/{id}', [App\Http\Controllers\ModifyController::class, 'modifyOccupationPost'])->name('modify.occupation.post');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/cast/{id}/like', [App\Http\Controllers\CastController::class, 'like'])->name('cast.like');
    
    Route::get('/cast/{id}/unlike', [App\Http\Controllers\CastController::class, 'unlike'])->name('cast.unlike');
    
    Route::get('/user_information/{uid}/like', [App\Http\Controllers\UserController::class, 'like'])->name('user.like');

    Route::get('/user_information/{uid}/unlike', [App\Http\Controllers\UserController::class, 'unlike'])->name('user.unlike');

    Route::get('/user_config', [App\Http\Controllers\UserController::class, 'config'])->name('user.config');

    Route::post('/user_config', [App\Http\Controllers\UserController::class, 'updateConfig'])->name('user.config.update');

    Route::get('/anime/{id}/score', [App\Http\Controllers\AnimeController::class, 'score'])->name('anime.score');

    Route::post('/anime/{id}/score', [App\Http\Controllers\AnimeController::class, 'postScore'])->name('anime.score.post');
});

Route::group(['middleware' => 'admin_auth'], function () {
    
    Route::get('/modify_list', [App\Http\Controllers\ModifyController::class, 'modifyListShow'])->name('modify.list.show');
    
    Route::post('/modify/anime/{id}/update', [App\Http\Controllers\ModifyController::class, 'modifyAnimeUpdate'])->name('modify.anime.update');
    
    Route::get('/modify/anime/{id}/delete', [App\Http\Controllers\ModifyController::class, 'modifyAnimeDelete'])->name('modify.anime.delete');
    
    Route::post('/modify/occupation/{id}/update', [App\Http\Controllers\ModifyController::class, 'modifyOccupationUpdate'])->name('modify.occupation.update');
    
    Route::get('/modify/occupation/{id}/delete', [App\Http\Controllers\ModifyController::class, 'modifyOccupationDelete'])->name('modify.occupation.delete');
});
