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
    private UserService $userService;
    private UserReviewService $userReviewService;
    private AnimeService $animeService;
    private CastService $castService;

    public function __construct(
        UserService $userService,
        UserReviewService $userReviewService,
        AnimeService $animeService,
        CastService $castService,
    ) {
        $this->userService = $userService;
        $this->userReviewService = $userReviewService;
        $this->animeService = $animeService;
        $this->castService = $castService;
    }

    /**
     * ユーザー情報を表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show($user_id, Request $request)
    {
        $user_information = $this->userService->getUserWithInformation($user_id, $request);
        return view('user_information', [
            'user_information' => $user_information,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの視聴予定アニメリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showWillWatchAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $will_watch_anime_list = $this->animeService->getWillWatchAnimeList($user);
        return view('will_watch_anime_list', [
            'user' => $user,
            'will_watch_anime_list' => $will_watch_anime_list,
        ]);
    }

    /**
     * ユーザーの得点を付けたレビューリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showScoreAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $score_review_list = $this->userReviewService->getLatestScoreReviewListWithAnimeOf($user);
        return view('score_anime_list', [
            'user' => $user,
            'score_review_list' => $score_review_list,
        ]);
    }

    /**
     * ユーザーのお気に入りユーザーリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showUserLikeUserList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $like_user_list = $this->userService->getLikeUserListWithLatestUserReview($user);
        return view('like_user_list', [
            'user' => $user,
            'like_user_list' => $like_user_list,
        ]);
    }

    /**
     * ユーザーの被お気に入りユーザーリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showUserLikedUserList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $liked_user_list = $this->userService->getLikedUserListWithLatestUserReview($user);
        return view('liked_user_list', [
            'user' => $user,
            'liked_user_list' => $liked_user_list,
        ]);
    }

    /**
     * ユーザーのお気に入り声優リストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showUserLikeCastList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $like_cast_list = $this->castService->getLikeCastList($user);
        return view('like_cast_list', [
            'user' => $user,
            'like_cast_list' => $like_cast_list,
        ]);
    }

    /**
     * ユーザーをお気に入りユーザーに登録
     *
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $this->userService->likeUser($user);
        $liked_user_count = $user->userLikedUsers->count();
        return response()->json(['likedUserCount' => $liked_user_count]);
    }

    /**
     * ユーザーをお気に入りユーザーから解除
     *
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $this->userService->unlikeUser($user);
        $liked_user_count = $user->userLikedUsers->count();
        return response()->json(['likedUserCount' => $liked_user_count]);
    }

    /**
     * ユーザーの基本情報変更画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function showUserConfig()
    {
        $user = $this->userService->getAuthUser();
        return view('user_config', [
            'user' => $user,
        ]);
    }

    /**
     * ユーザーの基本情報を更新
     *
     * @param ConfigRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUserConfig(ConfigRequest $request)
    {
        $this->userService->updateConfig($request);
        return redirect()->route('user_config.show')->with('flash_message', '登録が完了しました。');
    }

    /**
     * お気に入りユーザー内での統計表を表示
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showUserStatistics($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $user_anime_statistics = $this->animeService->getAnimeListForStatistics($user, $request);
        return view('user_statistics', [
            'user' => $user,
            'user_anime_statistics' => $user_anime_statistics,
            'median' => $request->median,
            'count' => $request->count,
            'bottom_year' => $request->bottom_year,
            'top_year' => $request->top_year,
        ]);
    }
}
