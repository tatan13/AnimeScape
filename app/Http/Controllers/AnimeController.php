<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\UserReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\SubmitScore;
use Illuminate\Support\Facades\DB;

class AnimeController extends Controller
{
    public function show(int $id)
    {
        $anime = Anime::find($id);

        if(!isset($anime)){
            return redirect(route('index'));
        }

        //アニメに紐づいているユーザーのレビューを取得
        $user_reviews = $anime->userReviews()->get()->sortBy('id');
        $anime_casts = $anime->actCasts;
        
        $myuser_score = null;
        //ログインユーザーの得点を取得
        if(Auth::check()){
            $myuser_view = $user_reviews->where('user_id', Auth::id())->first();
            if(isset($myuser_view->score)){
                $myuser_score = $myuser_view->score;
            }
        }

        return view('anime', [
            'anime' => $anime,
            'user_reviews' => $user_reviews,
            'myuser_score' => $myuser_score,
            'anime_casts' => $anime_casts,
        ]);
    }

    public function score(int $id)
    {
        $anime = Anime::find($id);

        if(!isset($anime)){
            return redirect(route('index'));
        }
        
        $user_review = $anime->userReviews()->where('user_id', Auth::id())->first();

        if(empty($user_review)){
            $user_review = new UserReview();
        }

        return view('score_anime', [
            'anime' => $anime,
            'user_review' => $user_review,
        ]);
    }

    public function result(int $id, SubmitScore $request)
    {
        $anime = Anime::find($id);

        if(!isset($anime)){
            return redirect(route('index'));
        }

        $score_result = $anime->userReviews()->where('user_id', Auth::id())->first();
        if(empty($score_result)){
            $score_result = new UserReview();
        }

        //入力した得点をuser_reviewsテーブルに格納
        $score_result->anime_id = $id;
        $score_result->score = $request->score;
        $score_result->one_word_comment = $request->one_comment;
        if(strcmp($request->spoiler, 'spoiler') == 0){
            $score_result->spoiler = 1;
        }else{
            $score_result->spoiler = 0;
        }
        if(strcmp($request->will_watch, 'will_watch') == 0){
            $score_result->will_watch = 1;
        }else{
            $score_result->will_watch = 0;
        }
        if(strcmp($request->watch, 'watch') == 0){
            $score_result->watch = 1;
            $score_result->will_watch = 0;
        }else{
            $score_result->watch = 0;
        }


        
        
        DB::transaction(function () use($score_result, $anime) {
            Auth::user()->userReviews()->save($score_result);
            
            //animesテーブルの得点情報を更新
            $user_reviews = $anime->userReviews()->get();
            $anime->median = $user_reviews->median('score');
            $anime->average = $user_reviews->avg('score');
            $anime->max = $user_reviews->max('score');
            $anime->min = $user_reviews->min('score');
            $anime->count = $user_reviews->count();
            $anime->save();
        });

        return redirect()->route('anime', [
            'id' => $id,
        ])->with('flash_message', '入力が完了しました。');
    }
}