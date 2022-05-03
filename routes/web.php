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

Route::get('/', [App\Http\Controllers\IndexController::class, 'show'])->name('index.show');

Route::get('/anime/{id}', [App\Http\Controllers\AnimeController::class, 'show'])->name('anime.show');

Route::get('/cast/{id}', [App\Http\Controllers\CastController::class, 'show'])->name('cast.show');

Route::get('/user_information/{user_name}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show');

Route::get('/user_information/{user_name}/will_watch_anime_list', [App\Http\Controllers\UserController::class, 'showWillWatchAnimeList'])->name('user_will_watch_anime_list.show');

Route::get('/user_information/{user_name}/like_user_list', [App\Http\Controllers\UserController::class, 'showUserLikeUserList'])->name('user_like_user_list.show');

Route::get('/user_information/{user_name}/liked_user_list', [App\Http\Controllers\UserController::class, 'showUserLikedUserList'])->name('user_liked_user_list.show');

Route::get('/user_information/{user_name}/like_cast_list', [App\Http\Controllers\UserController::class, 'showUserLikeCastList'])->name('user_like_cast_list.show');

Route::get('/user_information/{user_name}/statistics', [App\Http\Controllers\UserController::class, 'showUserStatistics'])->name('user_statistics.show');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'show'])->name('search.show');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact.show');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'post'])->name('contact.post');

Route::get('/anime_statistics', [App\Http\Controllers\StatisticsController::class, 'show'])->name('anime_statistics.show');

Route::get('/modify/anime/{id}', [App\Http\Controllers\ModifyController::class, 'showModifyAnime'])->name('modify_anime.show');

Route::post('/modify/anime/{id}', [App\Http\Controllers\ModifyController::class, 'postModifyAnime'])->name('modify_anime.post');

Route::get('/update_log', [App\Http\Controllers\IndexController::class, 'showUpdateLog'])->name('update_log.show');

Route::get('/privacy_policy', [App\Http\Controllers\IndexController::class, 'showPrivacyPolicy'])->name('privacy_policy.show');

Route::get('/modify/occupation/{id}', [App\Http\Controllers\ModifyController::class, 'showModifyOccupation'])->name('modify_occupation.show');

Route::post('/modify/occupation/{id}', [App\Http\Controllers\ModifyController::class, 'postModifyOccupation'])->name('modify_occupation.post');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/cast/{id}/like', [App\Http\Controllers\CastController::class, 'like'])->name('cast.like');
    
    Route::get('/cast/{id}/unlike', [App\Http\Controllers\CastController::class, 'unlike'])->name('cast.unlike');
    
    Route::get('/user_information/{user_name}/like', [App\Http\Controllers\UserController::class, 'like'])->name('user.like');

    Route::get('/user_information/{user_name}/unlike', [App\Http\Controllers\UserController::class, 'unlike'])->name('user.unlike');

    Route::get('/user_config', [App\Http\Controllers\UserController::class, 'showUserConfig'])->name('user_config.show');

    Route::post('/user_config', [App\Http\Controllers\UserController::class, 'postUserConfig'])->name('user_config.post');

    Route::get('/anime/{id}/review', [App\Http\Controllers\AnimeController::class, 'showAnimeReview'])->name('anime_review.show');

    Route::post('/anime/{id}/review', [App\Http\Controllers\AnimeController::class, 'postAnimeReview'])->name('anime_review.post');

    Route::get('/anime_review_list', [App\Http\Controllers\AnimeController::class, 'showAnimeReviewList'])->name('anime_review_list.show');

    Route::post('/anime_review_list', [App\Http\Controllers\AnimeController::class, 'postAnimeReviewList'])->name('anime_review_list.post');
});

Route::group(['middleware' => 'admin_auth'], function () {
    
    Route::get('/modify_list', [App\Http\Controllers\ModifyController::class, 'showModifyList'])->name('modify_list.show');
    
    Route::post('/modify/anime/{id}/update', [App\Http\Controllers\ModifyController::class, 'UpdateModifyAnime'])->name('modify_anime.update');
    
    Route::get('/modify/anime/{id}/delete', [App\Http\Controllers\ModifyController::class, 'deleteModifyAnime'])->name('modify_anime.delete');
    
    Route::post('/modify/occupation/{id}/update', [App\Http\Controllers\ModifyController::class, 'updateModifyOccupation'])->name('modify_occupation.update');
    
    Route::get('/modify/occupation/{id}/delete', [App\Http\Controllers\ModifyController::class, 'deleteModifyOccupation'])->name('modify_occupation.delete');
});
