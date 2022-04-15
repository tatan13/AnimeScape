<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReview;
use App\Models\Anime;
use App\Models\UserLikeUser;
use App\Library\Label;
use App\Services\ExceptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateConfig;

class UserController extends Controller
{
    private const COOR = [
        0 => '',
        1 => '冬',
        2 => '春',
        3 => '夏',
        4 => '秋',
    ];
    private $animeService;
    private $exceptionService;

    public function __construct(ExceptionService $exceptionService)
    {
        $this->exceptionService = $exceptionService;
    }

    /**
     * ユーザー情報を表示
     *
     * @param string $uid
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show($uid, Request $request)
    {
        $coorLabel = new Label((int)$request->coor);
        $coorLabel->setLabel(self::COOR);

        $user = User::where('uid', $uid)->with(['userLikeUsers', 'likeCasts'])->first();

        $this->exceptionService->render404IfNotExist($user);
//わかりづらい
        if (is_null($request->year)) {
            $user_reviews = $user->userReviews()->with('anime')->get();
        } elseif (is_null($request->coor)) {
            $user_reviews = $user->userReviews()->whereHas('anime', function ($query) use ($request) {
                $query->where('year', $request->year);
            })->with('anime')->get();
        } else {
            $user_reviews = $user->userReviews()->whereHas('anime', function ($query) use ($request) {
                $query->where('year', $request->year)->where('coor', $request->coor);
            })->with('anime')->get();
        }

        // ユーザーが100点を付けたアニメのレビューリストを取得
        $score_100_anime_reviews = $user_reviews->where('score', 100)->sortByDesc('score');

        // ユーザーがi点~i+4点を付けたアニメのレビューリストを取得
        for ($i = 95; $i >= 0; $i = $i - 5) {
            ${"score_" . $i . "_anime_reviews"} = $user_reviews
            ->whereNotNull('score')->whereBetween('score', [$i, $i + 4])->sortByDesc('score');
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

    /**
     * ユーザーの視聴予定アニメリストを表示
     *
     * @param string $uid
     * @return \Illuminate\View\View
     */
    public function showWillWatchList($uid)
    {
        $user = User::where('uid', $uid)->first();

        $this->exceptionService->render404IfNotExist($user);

        $user_reviews = $user->userReviews->where('will_watch', 1);

        return view('will_watch_list', [
            'user' => $user,
            'user_reviews' => $user_reviews,
        ]);
    }

    /**
     * ユーザーのお気に入りユーザーリストを表示
     *
     * @param string $uid
     * @return \Illuminate\View\View
     */
    public function showLikeUserList($uid)
    {
        $user = User::where('uid', $uid)->first();

        $this->exceptionService->render404IfNotExist($user);
        //共通
        $like_users = $user->userLikeUsers()->with(['userReviews' => function ($query) {
            $query->orderBy('updated_at', 'desc');
        }])->get();

        return view('like_user_list', [
            'user' => $user,
            'like_users' => $like_users,
        ]);
    }

    /**
     * ユーザーの被お気に入りユーザーリストを表示
     *
     * @param string $uid
     * @return \Illuminate\View\View
     */
    public function showLikedUserList($uid)
    {
        $user = User::where('uid', $uid)->first();

        $this->exceptionService->render404IfNotExist($user);
        //共通
        $liked_users = $user->userLikedUsers()->with(['userReviews' => function ($query) {
            $query->orderBy('updated_at', 'desc');
        }])->get();

        return view('liked_user_list', [
            'user' => $user,
            'liked_users' => $liked_users,
        ]);
    }

    /**
     * ユーザーのお気に入り声優リストを表示
     *
     * @param string $uid
     * @return \Illuminate\View\View
     */
    public function showLikeCastList($uid)
    {
        $user = User::where('uid', $uid)->first();

        $this->exceptionService->render404IfNotExist($user);

        $like_casts = $user->likeCasts;

        return view('like_cast_list', [
            'user' => $user,
            'like_casts' => $like_casts,
        ]);
    }

    /**
     * ユーザーをお気に入りユーザーに登録
     *
     * @param string $uid
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($uid)
    {
        $user = User::where('uid', $uid)->first();


        $this->exceptionService->render404IfNotExist($user);

        if (Auth::check()) {
            $auth_user = Auth::user();
            if (!$auth_user->isLikeUser($uid) && $auth_user->id != $user->id) {
                $auth_user->userLikeUsers()->attach($user->id);
            }
        }

        $liked_user_count = $user->userLikedUsers->count();

        return response()->json(['likedUserCount' => $liked_user_count]);
    }

    /**
     * ユーザーをお気に入りユーザーから解除
     *
     * @param string $uid
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike($uid)
    {
        $user = User::where('uid', $uid)->first();

        $this->exceptionService->render404IfNotExist($user);

        if (Auth::check()) {
            $auth_user = Auth::user();
            if ($auth_user->isLikeUser($uid) && $auth_user->id != $user->id) {
                $auth_user->userLikeUsers()->detach($user->id);
            }
        }

        $liked_user_count = $user->userLikedUsers->count();

        return response()->json(['likedUserCount' => $liked_user_count]);
    }

    /**
     * ユーザーの基本情報変更画面を表示
     *
     * @param string $uid
     * @return \Illuminate\View\View
     */
    public function config($uid)
    {
        if (Auth::check()) {
            if (strcmp(Auth::user()->uid, $uid) == 0) {
                $user = Auth::user();
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }

        return view('user_config', [
            'user' => $user,
        ]);
    }

    /**
     * ユーザーの基本情報変更画面を表示
     * @param UpdateConfig $request
     * @param string $uid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateConfig(UpdateConfig $request, $uid)
    {
        if (Auth::check()) {
            if (strcmp(Auth::user()->uid, $uid) == 0) {
                $user = Auth::user();
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }

        $user->email = $request->email;
        $user->onewordcomment = $request->one_comment;
        $user->twitter = $request->twitter;
        $user->birth = $request->birth;
        if (strcmp($request->sex, 'm') == 0) {
            $user->sex = true;
        } elseif (strcmp($request->sex, 'f') == 0) {
            $user->sex = false;
        }

        $user->save();

        return redirect()->route('user.config', ['uid' => $uid])->with('flash_message', '個人情報の登録が完了しました。');
    }

    /**
     * お気に入りユーザーの統計表を表示
     * @param string $uid
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function statistics($uid, Request $request)
    {
        $user = User::where('uid', $uid)->first();

        $this->exceptionService->render404IfNotExist($user);

        $req_median = $request->median ?? 0;
        $req_count = $request->count ?? 0;
        $bottom_year = $request->bottom_year ?? 0;
        $top_year = $request->top_year ?? 3000;

        $liked_users_id = $user->userLikeUsers->pluck('id');
        $liked_users_id->push($user->id);

        $users_reviews = UserReview::whereIn('user_id', $liked_users_id)->get()->whereNotNull('score');

        $users_animes_reviews = $users_reviews->groupBy('anime_id');
        $animes = collect([]);
        foreach ($users_animes_reviews as $anime_id => $users_anime_reviews) {
            $anime = Anime::find($anime_id);
            $median = $users_anime_reviews->median('score');
            $count = $users_anime_reviews->count();
            $watch = $users_anime_reviews->contains('user_id', $user->id) ? '済' : '';
            if (
                $anime->year >= $bottom_year && $anime->year <= $top_year &&
                $count >= $req_count && $median >= $req_median
            ) {
                $animes->push([
                'anime' => $anime,
                'median' => $median,
                'count' => $count,
                'watch' => $watch,
                ]);
            }
        }

        $animes = $animes->sortByDesc('median');

        return view('user_statistics', [
            'user' => $user,
            'animes' => $animes,
            'median' => $request->median,
            'count' => $request->count,
            'bottom_year' => $request->bottom_year,
            'top_year' => $request->top_year,
        ]);
    }
}
