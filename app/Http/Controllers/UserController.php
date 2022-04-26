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
    private $userService;
    private $animeService;
    private $castService;

    public function __construct(
        UserService $userService,
        AnimeService $animeService,
        CastService $castService,
    ) {
        $this->userService = $userService;
        $this->animeService = $animeService;
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
        $user_information = $this->userService->getUserWithInformation($uid, $request);
        return view('user_information', [
            'user_information' => $user_information,
            'year' => $request->year,
            'coor' => $request->coor,
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
    public function showUserLikeUserList($uid)
    {
        $user = $this->userService->getUser($uid);
        $like_user_list = $this->userService->getLikeUserListWithLatestUserReview($user);
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
    public function showUserLikedUserList($uid)
    {
        $user = $this->userService->getUser($uid);
        $liked_user_list = $this->userService->getLikedUserListWithLatestUserReview($user);
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
    public function showUserLikeCastList($uid)
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
     * @param string $uid
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showUserStatistics($uid, Request $request)
    {
        $user = $this->userService->getUser($uid);
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
