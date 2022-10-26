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
use App\Services\CreaterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;

class UserController extends Controller
{
    private UserService $userService;
    private UserReviewService $userReviewService;
    private AnimeService $animeService;
    private CastService $castService;
    private CreaterService $createrService;

    public function __construct(
        UserService $userService,
        UserReviewService $userReviewService,
        AnimeService $animeService,
        CastService $castService,
        CreaterService $createrService,
    ) {
        $this->userService = $userService;
        $this->userReviewService = $userReviewService;
        $this->animeService = $animeService;
        $this->castService = $castService;
        $this->createrService = $createrService;
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
     * ユーザーの感想リストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showCommentAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $comment_anime_list = $this->animeService->getLatestCommentAnimeListWithUserReviewOf($user);
        return view('comment_anime_list', [
            'user' => $user,
            'comment_anime_list' => $comment_anime_list,
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
        $will_watch_anime_list = $this->animeService->getLatestWillWatchAnimeListWithCompaniesWithUserReviewOf($user);
        return view('will_watch_anime_list', [
            'user' => $user,
            'will_watch_anime_list' => $will_watch_anime_list,
        ]);
    }

    /**
     * ユーザーの視聴済みアニメリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showWatchAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $watch_anime_list = $this->animeService->getWatchAnimeListWithCompaniesWithUserReviewLatestOf($user);
        return view('watch_anime_list', [
            'user' => $user,
            'watch_anime_list' => $watch_anime_list,
        ]);
    }

    /**
     * ユーザーの視聴中アニメリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showNowWatchAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $now_watch_anime_list = $this->animeService->getLatestNowWatchAnimeListWithCompaniesOf($user);
        return view('now_watch_anime_list', [
            'user' => $user,
            'now_watch_anime_list' => $now_watch_anime_list,
        ]);
    }

    /**
     * ユーザーのギブアップしたアニメリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showGiveUpAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $give_up_anime_list = $this->animeService->getLatestGiveUpAnimeListWithCompaniesOf($user);
        return view('give_up_anime_list', [
            'user' => $user,
            'give_up_anime_list' => $give_up_anime_list,
        ]);
    }

    /**
     * ユーザーの得点を付けたアニメリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showScoreAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $score_anime_list = $this->animeService->getScoreAnimeListWithCompaniesWithUserReviewLatestOf($user);
        return view('score_anime_list', [
            'user' => $user,
            'score_anime_list' => $score_anime_list,
        ]);
    }

    /**
     * ユーザーの視聴完了前得点を付けたアニメリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showBeforeScoreAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $before_score_anime_list = $this->animeService
        ->getBeforeScoreAnimeListWithCompaniesWithUserReviewLatestOf($user);
        return view('before_score_anime_list', [
            'user' => $user,
            'before_score_anime_list' => $before_score_anime_list,
        ]);
    }

    /**
     * ユーザーの視聴完了前一言感想を付けたアニメを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showBeforeCommentAnimeList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $before_comment_anime_list = $this->animeService->getLatestBeforeCommentAnimeListWithUserReviewOf($user);
        return view('before_comment_anime_list', [
            'user' => $user,
            'before_comment_anime_list' => $before_comment_anime_list,
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
     * ユーザーのお気に入りクリエイターリストを表示
     *
     * @param int $user_id
     * @return \Illuminate\View\View
     */
    public function showUserLikeCreaterList($user_id)
    {
        $user = $this->userService->getUserById($user_id);
        $like_creater_list = $this->createrService->getLikeCreaterList($user);
        return view('like_creater_list', [
            'user' => $user,
            'like_creater_list' => $like_creater_list,
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

    /**
     * ユーザーのアニメコメントを表示
     * @param int $user_review_id
     * @return \Illuminate\View\View
     */
    public function showUserAnimeComment($user_review_id)
    {
        $user_review = $this->userReviewService->getUserReviewWithAnimeAndUser($user_review_id);
        return view('anime_comment', [
            'user_review' => $user_review,
        ]);
    }
}
