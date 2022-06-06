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

Route::get('/anime/{anime_id}', [App\Http\Controllers\AnimeController::class, 'show'])->name('anime.show');

Route::get('/user_anime_comment/{user_review_id}', [App\Http\Controllers\UserController::class, 'showUserAnimeComment'])->name('user_anime_comment.show');

Route::get('/anime/{anime_id}/delete_request', [App\Http\Controllers\ModifyController::class, 'showDeleteAnimeRequest'])->name('delete_anime_request.show');

Route::post('/anime/{anime_id}/delete_request', [App\Http\Controllers\ModifyController::class, 'postDeleteAnimeRequest'])->name('delete_anime_request.post');

Route::get('/add_anime_request', [App\Http\Controllers\ModifyController::class, 'showAddAnimeRequest'])->name('add_anime_request.show');

Route::post('/add_anime_request', [App\Http\Controllers\ModifyController::class, 'postAddAnimeRequest'])->name('add_anime_request.post');

Route::get('/cast/{cast_id}', [App\Http\Controllers\CastController::class, 'show'])->name('cast.show');

Route::get('/add_cast_request', [App\Http\Controllers\ModifyController::class, 'showAddCastRequest'])->name('add_cast_request.show');

Route::post('/add_cast_request', [App\Http\Controllers\ModifyController::class, 'postAddCastRequest'])->name('add_cast_request.post');

Route::get('/cast_list', [App\Http\Controllers\CastController::class, 'showList'])->name('cast_list.show');

Route::get('/company/{company_id}', [App\Http\Controllers\CompanyController::class, 'show'])->name('company.show');

Route::get('/company/{company_id}/delete_request', [App\Http\Controllers\ModifyController::class, 'showDeleteCompanyRequest'])->name('delete_company_request.show');

Route::post('/company/{company_id}/delete_request', [App\Http\Controllers\ModifyController::class, 'postDeleteCompanyRequest'])->name('delete_company_request.post');

Route::get('/user_information/{user_id}', [App\Http\Controllers\UserController::class, 'show'])->name('user.show');

Route::get('/user_information/{user_id}/score_anime_list', [App\Http\Controllers\UserController::class, 'showScoreAnimeList'])->name('user_score_anime_list.show');

Route::get('/user_information/{user_id}/will_watch_anime_list', [App\Http\Controllers\UserController::class, 'showWillWatchAnimeList'])->name('user_will_watch_anime_list.show');

Route::get('/user_information/{user_id}/watch_anime_list', [App\Http\Controllers\UserController::class, 'showWatchAnimeList'])->name('user_watch_anime_list.show');

Route::get('/user_information/{user_id}/now_watch_anime_list', [App\Http\Controllers\UserController::class, 'showNowWatchAnimeList'])->name('user_now_watch_anime_list.show');

Route::get('/user_information/{user_id}/give_up_anime_list', [App\Http\Controllers\UserController::class, 'showGiveUpAnimeList'])->name('user_give_up_anime_list.show');

Route::get('/user_information/{user_id}/like_user_list', [App\Http\Controllers\UserController::class, 'showUserLikeUserList'])->name('user_like_user_list.show');

Route::get('/user_information/{user_id}/liked_user_list', [App\Http\Controllers\UserController::class, 'showUserLikedUserList'])->name('user_liked_user_list.show');

Route::get('/user_information/{user_id}/like_cast_list', [App\Http\Controllers\UserController::class, 'showUserLikeCastList'])->name('user_like_cast_list.show');

Route::get('/user_information/{user_id}/statistics', [App\Http\Controllers\UserController::class, 'showUserStatistics'])->name('user_statistics.show');

Route::get('/search', [App\Http\Controllers\SearchController::class, 'show'])->name('search.show');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'show'])->name('contact.show');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'post'])->name('contact.post');

Route::get('/anime_statistics', [App\Http\Controllers\StatisticsController::class, 'show'])->name('anime_statistics.show');

Route::get('/anime/{anime_id}/modify_request', [App\Http\Controllers\ModifyController::class, 'showModifyAnimeRequest'])->name('modify_anime_request.show');

Route::post('/anime/{anime_id}/modify_request', [App\Http\Controllers\ModifyController::class, 'postModifyAnimeRequest'])->name('modify_anime_request.post');

Route::get('/modify_request_list', [App\Http\Controllers\ModifyController::class, 'showModifyRequestList'])->name('modify_request_list.show');

Route::get('/add_anime_log', [App\Http\Controllers\ModifyController::class, 'showAddAnimeLog'])->name('add_anime_log.show');

Route::get('/add_cast_log', [App\Http\Controllers\ModifyController::class, 'showAddCastLog'])->name('add_cast_log.show');

Route::get('/update_log', [App\Http\Controllers\IndexController::class, 'showUpdateLog'])->name('update_log.show');

Route::get('/site_information', [App\Http\Controllers\IndexController::class, 'showSiteInformation'])->name('site_information.show');

Route::get('/privacy_policy', [App\Http\Controllers\IndexController::class, 'showPrivacyPolicy'])->name('privacy_policy.show');

Route::get('/cast/{cast_id}/modify_request', [App\Http\Controllers\ModifyController::class, 'showModifyCastRequest'])->name('modify_cast_request.show');

Route::post('/cast/{cast_id}/modify_request', [App\Http\Controllers\ModifyController::class, 'postModifyCastRequest'])->name('modify_cast_request.post');

Route::get('/cast/{cast_id}/delete_request', [App\Http\Controllers\ModifyController::class, 'showDeleteCastRequest'])->name('delete_cast_request.show');

Route::post('/cast/{cast_id}/delete_request', [App\Http\Controllers\ModifyController::class, 'postDeleteCastRequest'])->name('delete_cast_request.post');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/cast/{cast_id}/like', [App\Http\Controllers\CastController::class, 'like'])->name('cast.like');
    
    Route::get('/cast/{cast_id}/unlike', [App\Http\Controllers\CastController::class, 'unlike'])->name('cast.unlike');
    
    Route::get('/user_information/{user_id}/like', [App\Http\Controllers\UserController::class, 'like'])->name('user.like');
    
    Route::get('/user_information/{user_id}/unlike', [App\Http\Controllers\UserController::class, 'unlike'])->name('user.unlike');
    
    Route::get('/user_config', [App\Http\Controllers\UserController::class, 'showUserConfig'])->name('user_config.show');
    
    Route::post('/user_config', [App\Http\Controllers\UserController::class, 'postUserConfig'])->name('user_config.post');
    
    Route::get('/anime/{anime_id}/review', [App\Http\Controllers\AnimeController::class, 'showAnimeReview'])->name('anime_review.show');
    
    Route::post('/anime/{anime_id}/review', [App\Http\Controllers\AnimeController::class, 'postAnimeReview'])->name('anime_review.post');
    
    Route::get('/anime_review_list', [App\Http\Controllers\AnimeController::class, 'showAnimeReviewList'])->name('anime_review_list.show');
    
    Route::post('/anime_review_list', [App\Http\Controllers\AnimeController::class, 'postAnimeReviewList'])->name('anime_review_list.post');
});

Route::group(['middleware' => 'admin_auth'], function () {
    Route::post('/delete/anime/{delete_anime_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveDeleteAnimeRequest'])->name('delete_anime_request.approve');

    Route::get('/delete/anime/{delete_anime_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectDeleteAnimeRequest'])->name('delete_anime_request.reject');

    Route::post('/add/anime/{add_anime_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveAddAnimeRequest'])->name('add_anime_request.approve');

    Route::get('/add/anime/{add_anime_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectAddAnimeRequest'])->name('add_anime_request.reject');
    
    Route::get('/anime/{anime_id}/delete', [App\Http\Controllers\ModifyController::class, 'deleteAnime'])->name('anime.delete');

    Route::post('/modify/anime/{modify_anime_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveModifyAnimeRequest'])->name('modify_anime_request.approve');
    
    Route::get('/modify/anime/{modify_anime_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectModifyAnimeRequest'])->name('modify_anime_request.reject');
    
    Route::post('/modify/occupations/{anime_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveModifyOccupationsRequest'])->name('modify_occupations_request.approve');
    
    Route::get('/modify/occupations/{anime_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectModifyOccupationsRequest'])->name('modify_occupations_request.reject');
    
    Route::post('/modify/cast/{modify_cast_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveModifyCastRequest'])->name('modify_cast_request.approve');
    
    Route::get('/modify/cast/{modify_cast_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectModifyCastRequest'])->name('modify_cast_request.reject');

    Route::post('/add/cast/{add_cast_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveAddCastRequest'])->name('add_cast_request.approve');

    Route::get('/add/cast/{add_cast_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectAddCastRequest'])->name('add_cast_request.reject');

    Route::post('/delete/cast/{delete_cast_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveDeleteCastRequest'])->name('delete_cast_request.approve');

    Route::get('/delete/cast/{delete_cast_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectDeleteCastRequest'])->name('delete_cast_request.reject');

    Route::post('/delete/company/{delete_company_id}/approve', [App\Http\Controllers\ModifyController::class, 'approveDeleteCompanyRequest'])->name('delete_company_request.approve');

    Route::get('/delete/company/{delete_company_id}/reject', [App\Http\Controllers\ModifyController::class, 'rejectDeleteCompanyRequest'])->name('delete_company_request.reject');
});
