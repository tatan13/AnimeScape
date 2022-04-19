<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReview;
use App\Models\Anime;
use App\Models\UserLikeUser;
use App\Services\UserService;
use App\Services\AnimeService;
use App\Services\UserReviewService;
use App\Services\CastService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;

class UserController extends Controller
{
    private $animeService;
    private $userReviewService;
    private $castService;

    public function __construct(
        UserService $userService,
        AnimeService $animeService,
        UserReviewService $userReviewService,
        CastService $castService,
    ) {
        $this->userService = $userService;
        $this->animeService = $animeService;
        $this->userReviewService = $userReviewService;
        $this->castService = $castService;
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
        $user = $this->userService->getUserWithLikeUsersAndLikeCasts($uid);
        $user_reviews = $this->userReviewService->getUserReviewsOfUserFor($user, $request);
        /*
        $user_reviews = $user_reviews->groupBy(function ($user_reviews) {
            for ($i = 100; $i >= 0; $i = $i - 5) {
                print('h ');
                if ($user_reviews['score'] >= $i) {
                    return  'score_'. $i . '_anime_reviews';
                }
            }
        })->map(function($group) {return $group->sortByDesc('score');});
        dd($user_reviews);
        */
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
            'coor' => $request->coor,
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
    public function showWillWatchAnimeList($uid)
    {
        $user = $this->userService->getUser($uid);
        $will_watch_anime_list = $this->animeService->getWillWatchAnimeList($user);
        return view('will_watch_anime_list', [
            'user' => $user,
            'will_watch_anime_list' => $will_watch_anime_list,
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
        $user = $this->userService->getUser($uid);
        $like_user_list = $this->userService->getLikeUserList($user);
        return view('like_user_list', [
            'user' => $user,
            'like_user_list' => $like_user_list,
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
        $user = $this->userService->getUser($uid);
        $liked_user_list = $this->userService->getLikedUserList($user);
        return view('liked_user_list', [
            'user' => $user,
            'liked_user_list' => $liked_user_list,
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
        $user = $this->userService->getUser($uid);
        $like_cast_list = $this->castService->getLikeCastList($user);
        return view('like_cast_list', [
            'user' => $user,
            'like_cast_list' => $like_cast_list,
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
        $user = $this->userService->getUser($uid);
        $this->userService->likeUser($user);
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
        $user = $this->userService->getUser($uid);
        $this->userService->unlikeUser($user);
        $liked_user_count = $user->userLikedUsers->count();
        return response()->json(['likedUserCount' => $liked_user_count]);
    }

    /**
     * ユーザーの基本情報変更画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function config()
    {
        $user = $this->userService->getAuthUser();
        return view('user_config', [
            'user' => $user,
        ]);
    }

    /**
     * ユーザーの基本情報変更画面を表示
     * 
     * @param ConfigRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateConfig(ConfigRequest $request)
    {
        $this->userService->updateConfig($request);
        return redirect()->route('user.config')->with('flash_message', '登録が完了しました。');
    }

    /**
     * お気に入りユーザーの統計表を表示
     * @param string $uid
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function statistics($uid, Request $request)
    {
        $user = $this->userService->getUser($uid);
        //$anime_list = $this->animeService->getAnimeListForStatistics($user, $request);
        $req_median = $request->median ?? 0;
        $req_count = $request->count ?? 0;
        $bottom_year = $request->bottom_year ?? 0;
        $top_year = $request->top_year ?? 3000;

        $like_users_id = $user->userLikeUsers->pluck('id');
        $like_users_id->push($user->id);

        $anime_list = Anime::whereHas('userReviews', function ($query) use ($like_users_id) {
            $query->whereIn('user_id', $like_users_id)->whereNotNull('score');
        })->with('userReviews', function ($query) use ($like_users_id) {
            $query->whereIn('user_id', $like_users_id)->whereNotNull('score')->with('user', function ($query) {
                $query->select('id', 'uid');
            });
        })->select(['id', 'title', 'year', 'coor'])->get()->map(function ($anime) use ($user) {
            $anime['median'] = $anime->userReviews->median('score');
            $anime['count'] = $anime->userReviews->count();
            $anime['isContainMe'] = $anime->userReviews->contains('user_id', $user->id) ? 1 : 0;
            return $anime;
        })->sortByDesc('median')->whereBetWeen('year', [$bottom_year, $top_year])->where('count', '>=', $req_count)->where('median', '>=', $req_median);

        return view('user_statistics', [
            'user' => $user,
            'anime_list' => $anime_list,
            'median' => $request->median,
            'count' => $request->count,
            'bottom_year' => $request->bottom_year,
            'top_year' => $request->top_year,
        ]);
    }
}
