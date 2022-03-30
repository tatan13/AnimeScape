<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReview;
use App\Models\Anime;
use App\Models\UserLikeUser;

use App\Library\Label;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateConfig;

class UserController extends Controller
{
    const COOR = [
        1 => '冬',
        2 => '春',
        3 => '夏',
        4 => '秋',
    ];
    public function show($uid, Request $request)
    {
        $coorLabel = new Label($request->coor);
        $coorLabel->setLabel(self::COOR);

        $user = User::where('uid', $uid)->first();
        if(!empty($user)){
            if(is_null($request->year)){
                $user_reviews = $user->user_reviews;
            }elseif(is_null($request->coor)){
                $user_reviews = $user->user_reviews()->whereHas('anime', function($query) use ($request){
                    $query->where('year', $request->year);
                })->get();
            }else{
                $user_reviews = $user->user_reviews()->whereHas('anime', function($query) use ($request){
                    $query->where('year', $request->year)->where('coor', $request->coor);
                })->get();
            }
        }else{
            return redirect(route('index'));
        }

        //ユーザーが100点を付けたアニメのレビューリストを取得
        $score_100_anime_reviews = $user_reviews->where('score', 100)->sortByDesc('score');

        //ユーザーがi点~i+4点を付けたアニメのレビューリストを取得
        for($i=95;$i>=0;$i=$i-5){
            ${"score_".$i."_anime_reviews"} = $user_reviews->whereNotNull('score')->whereBetween('score', [$i, $i+4])->sortByDesc('score');
        }

        return view('user_information', [
            'user' => $user,
            'year' => $request->year,
            'coor' => $coorLabel,
            'score_count' => $user_reviews->whereNotNull('score')->count(),
            'score_average' => (int)$user_reviews->avg('score'),
            'score_median' => $user_reviews->median('score'),
            'one_comments_count' => $user_reviews->whereNotNull('one_word_comment')->count(),
            'long_comments_count' => $user_reviews->whereNotNull('long_word_comment')->count(),
            'will_watches_count' => $user_reviews->where('will_watch', 1)->count(),
            'watches_count' => $user_reviews->where('watch', 1)->count(),
            'score_100_anime_reviews' => $score_100_anime_reviews,
            'score_95_anime_reviews' => $score_95_anime_reviews,
            'score_90_anime_reviews' => $score_90_anime_reviews,
            'score_85_anime_reviews' => $score_85_anime_reviews,
            'score_80_anime_reviews' => $score_80_anime_reviews,
            'score_75_anime_reviews' => $score_75_anime_reviews,
            'score_70_anime_reviews' => $score_70_anime_reviews,
            'score_65_anime_reviews' => $score_65_anime_reviews,
            'score_60_anime_reviews' => $score_60_anime_reviews,
            'score_55_anime_reviews' => $score_55_anime_reviews,
            'score_50_anime_reviews' => $score_50_anime_reviews,
            'score_45_anime_reviews' => $score_45_anime_reviews,
            'score_40_anime_reviews' => $score_40_anime_reviews,
            'score_35_anime_reviews' => $score_35_anime_reviews,
            'score_30_anime_reviews' => $score_30_anime_reviews,
            'score_25_anime_reviews' => $score_25_anime_reviews,
            'score_20_anime_reviews' => $score_20_anime_reviews,
            'score_15_anime_reviews' => $score_15_anime_reviews,
            'score_10_anime_reviews' => $score_10_anime_reviews,
            'score_5_anime_reviews' => $score_5_anime_reviews,
            'score_0_anime_reviews' => $score_0_anime_reviews,
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
            $auth_user = Auth::user();
            if(!$auth_user->isLikeUser($uid) && $auth_user->id != $user->id){
                $auth_user->user_like_users()->attach($user->id);
            }
        }
        
        $liked_user_count = $user->user_liked_users->count();

        return response()->json(['likedUserCount' => $liked_user_count]);
    }

    public function dislike($uid)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        if(Auth::check()){
            $auth_user = Auth::user();
            if($auth_user->isLikeUser($uid) && $auth_user->id != $user->id){
                $auth_user->user_like_users()->detach($user->id);
            }
        }
        
        $liked_user_count = $user->user_liked_users->count();

        return response()->json(['likedUserCount' => $liked_user_count]);
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

    public function statistics($uid, Request $request)
    {
        $user = User::where('uid', $uid)->first();

        if(!isset($user)){
            return redirect(route('index'));
        }

        $req_median = $request->median ?? 0;
        $req_count = $request->count ?? 0;
        $bottom_year = $request->bottom_year ?? 0;
        $top_year = $request->top_year ?? 3000;

        $liked_users_id = $user->user_like_users->pluck('id');
        $liked_users_id->push($user->id);

        $users_reviews = UserReview::whereIn('user_id', $liked_users_id)->get()->whereNotNull('score');

        $users_animes_reviews = $users_reviews->groupBy('anime_id');
        $animes = collect([]);
        foreach($users_animes_reviews as $anime_id => $users_anime_reviews){
            $anime = Anime::find($anime_id);
            $median = $users_anime_reviews->median('score');
            $count = $users_anime_reviews->count();
            $watch = $users_anime_reviews->contains('user_id', $user->id) ? '済' : '';
            if($anime->year >= $bottom_year && $anime->year <= $top_year && $count >= $req_count && $median >= $req_median )
            $animes->push([
                'anime' => $anime,
                'median' => $median,
                'count' => $count,
                'watch'=> $watch,
            ]);
        }

        $animes = $animes->sortByDesc('median');

        return view('user_statistics',[
            'user' => $user,
            'animes' => $animes,
            'median' => $request->median,
            'count' => $request->count,
            'bottom_year' => $request->bottom_year,
            'top_year' => $request->top_year,
        ]);
    }
}