<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReview;
use App\Models\Anime;
use App\Models\UserLikeUser;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateConfig;

class UserController extends Controller
{
    public function show($uid)
    {
        $user = User::where('uid', $uid)->first();
        if(!empty($user)){
            $user_reviews = $user->user_reviews;
        }else{
            return redirect(route('index'));
        }

        //ユーザーの統計情報
        $user_information = [
            'user' => $user,
            'score_count' => $user_reviews->whereNotNull('score')->count(),
            'score_average' => (int)$user_reviews->avg('score'),
            'score_median' => $user_reviews->median('score'),
            'one_comments_count' => $user_reviews->whereNotNull('one_word_comment')->count(),
            'long_comments_count' => $user_reviews->whereNotNull('long_word_comment')->count(),
            'will_watches_count' => $user_reviews->where('will_watch', 1)->count(),
            'watches_count' => $user_reviews->where('watch', 1)->count(),
        ];

        
        $score_n_animes = array();

        //ユーザーが100点を付けたアニメのリストを取得
        $score_100_anime_reviews = $user_reviews->where('score', 100)->sortByDesc('score');
        $score_100_animes = array();
        foreach($score_100_anime_reviews as $anime_review){
            $anime = Anime::find($anime_review->anime_id);
            $my_anime_score = $anime_review->score;
            $score_100_animes[] = [
                'anime' => $anime,
                'my_anime_score' => $my_anime_score,
            ];
        }
        $score_n_animes['score_100_animes']= $score_100_animes;

        //ユーザーがi点~i+4点を付けたアニメのリストを取得
        for($i=95;$i>=0;$i=$i-5){
            ${"score_".$i."_anime_reviews"} = $user_reviews->whereNotNull('score')->whereBetween('score', [$i, $i+4])->sortByDesc('score');
            ${"score_".$i."_animes"} = array();
            foreach(${"score_".$i."_anime_reviews"} as $anime_review){
                $anime = Anime::find($anime_review->anime_id);
                $my_anime_score = $anime_review->score;
                ${"score_".$i."_animes"}[] = [
                    'anime' => $anime,
                    'my_anime_score' => $my_anime_score,
                ];
            }
            $score_n_animes["score_{$i}_animes"]= ${"score_".$i."_animes"};
        }

        return view('user_information', [
            'user_information' => $user_information,
            'score_n_animes' => $score_n_animes,
        ]);
    }

    public function show_will_watch_list($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        $user_reviews = $user->user_reviews->where('will_watch', 1);

        return view('will_watch_list',[
            'user' => $user,
            'user_reviews' => $user_reviews,
        ]);
    }

    public function show_like_user_list($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        $like_users = $user->user_like_users;

        return view('like_user_list',[
            'user' => $user,
            'like_users' => $like_users,
        ]);
    }

    public function show_liked_user_list($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        $liked_users = $user->user_liked_users;

        return view('liked_user_list',[
            'user' => $user,
            'liked_users' => $liked_users,
        ]);
    }

    public function show_like_cast_list($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        $like_casts = $user->like_casts;

        return view('like_cast_list',[
            'user' => $user,
            'like_casts' => $like_casts,
        ]);
    }

    public function like($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        if(Auth::check()){
            $user_like_user = Auth::user()->user_like_users->where('liked_user_id', $user->id)->first();
            if(!isset($user_like_user)){
                $user_like_user = new UserLikeUser();
                $user_like_user->liked_user_id = User::where('uid', $uid)->first()->id;
                Auth::user()->user_like_users()->save($user_like_user);
            }
        }

        return redirect(route('user',['uid' => $uid]));
    }

    public function dislike($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        if(Auth::check()){
            $user_like_user = Auth::user()->user_like_users->where('liked_user_id', $user->id)->first();
            if(isset($user_like_user)){
                $user_like_user->delete();
            }
        }

        return redirect(route('user',['uid' => $uid]));
    }

    public function config($uid)
    {
        if(Auth::check()){
            if(strcmp(Auth::user()->uid, $uid) == 0){
                $user = Auth::user();
            }else{
                return redirect(route('index'));
            }
        }else{
            return redirect(route('index'));
        }

        return view('user_config',[
            'user' => $user,
        ]);
    }

    public function updateconfig(UpdateConfig $request, $uid)
    {
        if(Auth::check()){
            if(strcmp(Auth::user()->uid, $uid) == 0){
                $user = Auth::user();
            }else{
                return redirect(route('index'));
            }
        }else{
            return redirect(route('index'));
        }

        $user->email = $request->email;
        $user->onewordcomment = $request->one_comment;
        $user->twitter = $request->twitter;
        $user->birth = $request->birth;
        if(strcmp($request->sex, 'm') == 0){
            $user->sex = TRUE;
        }elseif(strcmp($request->sex, 'f') == 0){
            $user->sex = FALSE;
        }

        $user->save();

        return redirect()->route('user.config', ['uid' => $uid])->with('flash_message', '個人情報の登録が完了しました。');
    }
}