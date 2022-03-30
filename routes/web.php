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

Route::get('/user_information/{uid}/will_watch_list', [App\Http\Controllers\UserController::class, 'show_will_watch_list'])->name('user.will_watch_list');

Route::get('/user_information/{uid}/like_user_list', [App\Http\Controllers\UserController::class, 'show_like_user_list'])->name('user.like_user_list');

Route::get('/user_information/{uid}/liked_user_list', [App\Http\Controllers\UserController::class, 'show_liked_user_list'])->name('user.liked_user_list');

Route::get('/user_information/{uid}/like_cast_list', [App\Http\Controllers\UserController::class, 'show_like_cast_list'])->name('user.like_cast_list');

Route::get('/user_information/{uid}/statistics', [App\Http\Controllers\UserController::class, 'statistics'])->name('user.statistics');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'post'])->name('contact.post');

Route::get('/all_statistics/{category}', [App\Http\Controllers\StatisticsController::class, 'show_all'])->name('all_statistics');

Route::get('/year_statistics/{category}', [App\Http\Controllers\StatisticsController::class, 'show_year'])->name('year_statistics');

Route::get('/coor_statistics/{category}', [App\Http\Controllers\StatisticsController::class, 'show_coor'])->name('coor_statistics');

Route::get('/modify/anime/{id}', [App\Http\Controllers\ModifyController::class, 'modify_anime_show'])->name('modify.anime.show');

Route::post('/modify/anime/{id}', [App\Http\Controllers\ModifyController::class, 'modify_anime_post'])->name('modify.anime.post');

Route::get('/update_log', [App\Http\Controllers\IndexController::class, 'updateLog'])->name('update_log');

Route::get('/modify/occupation/{id}', [App\Http\Controllers\ModifyController::class, 'modify_occupation_show'])->name('modify.occupation.show');

Route::post('/modify/occupation/{id}', [App\Http\Controllers\ModifyController::class, 'modify_occupation_post'])->name('modify.occupation.post');

Route::group(['middleware' => 'auth'], function() {
    
    Route::get('/cast/{id}/like', [App\Http\Controllers\CastController::class, 'like'])->name('cast.like');
    
    Route::get('/cast/{id}/dislike', [App\Http\Controllers\CastController::class, 'dislike'])->name('cast.dislike');
    
    Route::get('/user_information/{uid}/like', [App\Http\Controllers\UserController::class, 'like'])->name('user.like');

    Route::get('/user_information/{uid}/dislike', [App\Http\Controllers\UserController::class, 'dislike'])->name('user.dislike');

    Route::get('/user_information/{uid}/config', [App\Http\Controllers\UserController::class, 'config'])->name('user.config');

    Route::post('/user_information/{uid}/config', [App\Http\Controllers\UserController::class, 'updateconfig'])->name('user.config.update');

    Route::get('/anime/{id}/score', [App\Http\Controllers\AnimeController::class, 'score'])->name('score');

    Route::post('/anime/{id}/score', [App\Http\Controllers\AnimeController::class, 'result'])->name('result');

    Route::get('/modify_list', [App\Http\Controllers\ModifyController::class, 'modify_list_show'])->name('modify.list.show');
    
    Route::post('/modify/anime/{id}/update', [App\Http\Controllers\ModifyController::class, 'modify_anime_update'])->name('modify.anime.update');
    
    Route::get('/modify/anime/{id}/delete', [App\Http\Controllers\ModifyController::class, 'modify_anime_delete'])->name('modify.anime.delete');

    Route::post('/modify/occupation/{id}/update', [App\Http\Controllers\ModifyController::class, 'modify_occupation_update'])->name('modify.occupation.update');
    
    Route::get('/modify/occupation/{id}/delete', [App\Http\Controllers\ModifyController::class, 'modify_occupation_delete'])->name('modify.occupation.delete');
});