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
use App\Services\CompanyService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;
use Carbon\Carbon;

class UserController extends Controller
{
    private UserService $userService;
    private UserReviewService $userReviewService;
    private AnimeService $animeService;
    private CastService $castService;
    private CreaterService $createrService;
    private CompanyService $companyService;

    public function __construct(
        UserService $userService,
        UserReviewService $userReviewService,
        AnimeService $animeService,
        CastService $castService,
        CreaterService $createrService,
        CompanyService $companyService,
    ) {
        $this->userService = $userService;
        $this->userReviewService = $userReviewService;
        $this->animeService = $animeService;
        $this->castService = $castService;
        $this->createrService = $createrService;
        $this->companyService = $companyService;
    }

    /**
     * OAuth認証先にリダイレクト
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * OAuth認証の結果受け取り
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse.
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = Socialite::with($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('oauth_error', '予期せぬエラーが発生しました');
        }

        $expires_in = $providerUser->expiresIn;
        $expire_time = new Carbon('+' . $expires_in . ' seconds');
        if (Auth::check()) {
            User::where('id', Auth::id())->update([
                'unique_id' => $providerUser->getId(),
                'access_token' => $providerUser->token,
                'refresh_token' => $providerUser->refreshToken,
                'token_limit' => $expire_time,
            ]);
            return redirect('/user_config');
        }

        Auth::login(User::firstOrCreate([
            'unique_id' => $providerUser->getId()
        ], [
            'unique_id' => $providerUser->getId(),
            'name' => $providerUser->getNickName(),
            'access_token' => $providerUser->token,
            'refresh_token' => $providerUser->refreshToken,
            'token_limit' => $expire_time,
        ]), true);

        return redirect()->intended('/');
    }

    /**
     * ツイッター連携の解除
     *
     * @return \Illuminate\Http\RedirectResponse.
     */
    public function unlinkUserTwitter()
    {
        User::where('id', Auth::id())->update([
                'unique_id' => null,
                'access_token' => null,
                'refresh_token' => null,
                'token_limit' => null,
        ]);
        return redirect('/user_config')->with('twitter_unlink', 'ツイッター連携を解除しました。');
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
        $company_list = $this->companyService->getUserWatchReview10CompanyList($user_information, $request);
        $cast_list = $this->castService->getUserWatchReview10CastList($user_information, $request);
        return view('user_information', [
            'user_information' => $user_information,
            'company_list' => $company_list,
            'cast_list' => $cast_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの制作会社別視聴数を表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showWatchReviewCompanyList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $company_list = $this->companyService->getUserWatchReviewAllCompanyList($user, $request);
        return view('watch_review_company_list', [
            'user' => $user,
            'company_list' => $company_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの声優別視聴数を表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showWatchReviewCastList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $cast_list = $this->castService->getUserWatchReviewAllCastList($user, $request);
        return view('watch_review_cast_list', [
            'user' => $user,
            'cast_list' => $cast_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの感想リストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCommentAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $comment_anime_list = $this->animeService->getLatestCommentAnimeListWithUserReviewOf($user, $request);
        return view('comment_anime_list', [
            'user' => $user,
            'comment_anime_list' => $comment_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの視聴予定アニメリストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showWillWatchAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $will_watch_anime_list = $this->animeService
        ->getLatestWillWatchAnimeListWithCompaniesWithUserReviewOf($user, $request);
        return view('will_watch_anime_list', [
            'user' => $user,
            'will_watch_anime_list' => $will_watch_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの視聴済みアニメリストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showWatchAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $watch_anime_list = $this->animeService
        ->getWatchAnimeListWithCompaniesWithUserReviewLatestOf($user, $request);
        return view('watch_anime_list', [
            'user' => $user,
            'watch_anime_list' => $watch_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの視聴中アニメリストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showNowWatchAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $now_watch_anime_list = $this->animeService->getLatestNowWatchAnimeListWithCompaniesOf($user, $request);
        return view('now_watch_anime_list', [
            'user' => $user,
            'now_watch_anime_list' => $now_watch_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーのギブアップしたアニメリストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showGiveUpAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $give_up_anime_list = $this->animeService->getLatestGiveUpAnimeListWithCompaniesOf($user, $request);
        return view('give_up_anime_list', [
            'user' => $user,
            'give_up_anime_list' => $give_up_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの得点を付けたアニメリストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showScoreAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $score_anime_list = $this->animeService->getScoreAnimeListWithCompaniesWithUserReviewLatestOf($user, $request);
        return view('score_anime_list', [
            'user' => $user,
            'score_anime_list' => $score_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの視聴完了前得点を付けたアニメリストを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showBeforeScoreAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $before_score_anime_list = $this->animeService
        ->getBeforeScoreAnimeListWithCompaniesWithUserReviewLatestOf($user, $request);
        return view('before_score_anime_list', [
            'user' => $user,
            'before_score_anime_list' => $before_score_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
        ]);
    }

    /**
     * ユーザーの視聴完了前一言感想を付けたアニメを表示
     *
     * @param int $user_id
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showBeforeCommentAnimeList($user_id, Request $request)
    {
        $user = $this->userService->getUserById($user_id);
        $before_comment_anime_list = $this->animeService
        ->getLatestBeforeCommentAnimeListWithUserReviewOf($user, $request);
        return view('before_comment_anime_list', [
            'user' => $user,
            'before_comment_anime_list' => $before_comment_anime_list,
            'year' => $request->year,
            'coor' => $request->coor,
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
        $user_review = $this->userReviewService->getUserReviewWithAnimeAndUserNotNullOneWordComment($user_review_id);
        return view('anime_comment', [
            'user_review' => $user_review,
        ]);
    }

    /**
     * ユーザーのアニメの視聴完了前コメントを表示
     * @param int $user_review_id
     * @return \Illuminate\View\View
     */
    public function showUserAnimeBeforeComment($user_review_id)
    {
        $user_review = $this->userReviewService->getUserReviewForAnimeBeforeComment($user_review_id);
        return view('anime_before_comment', [
            'user_review' => $user_review,
        ]);
    }

    /**
     * 新着一言感想一覧を表示
     * @return \Illuminate\View\View
     */
    public function showNewCommentList()
    {
        $user_reviews_latest_comment = $this->userReviewService
        ->getUserReviewListLatestCommentWithAnimeAndUser();
        return view('new_comment_list', [
            'user_reviews_latest_comment' => $user_reviews_latest_comment,
        ]);
    }

    /**
     * 新着視聴完了前一言感想一覧を表示
     * @return \Illuminate\View\View
     */
    public function showNewBeforeCommentList()
    {
        $user_reviews_latest_before_comment = $this->userReviewService
        ->getUserReviewListLatestBeforeCommentWithAnimeAndUser();
        return view('new_before_comment_list', [
            'user_reviews_latest_before_comment' => $user_reviews_latest_before_comment,
        ]);
    }
}
