<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimeService;
use App\Services\UserReviewService;

class IndexController extends Controller
{
    private AnimeService $animeService;
    private UserReviewService $userReviewService;

    public function __construct(
        AnimeService $animeService,
        UserReviewService $userReviewService
    ) {
        $this->animeService = $animeService;
        $this->userReviewService = $userReviewService;
    }

    /**
     * インデックスページを表示
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $animes = $this->animeService->getNowCoorAnimeListWithCompaniesAndWithMyReviews();
        $user_reviews_latest_comment = $this->userReviewService
        ->getUserReviewListLatestCommentLimitWithAnimeAndUser();
        $user_reviews_latest_before_comment = $this->userReviewService
        ->getUserReviewListLatestBeforeCommentLimitWithAnimeAndUser();
        // $recommend_anime_list = $this->animeService->getRecommendAnimeListWithCompanies();
        return view('index', [
            'animes' => $animes,
            'user_reviews_latest_comment' => $user_reviews_latest_comment,
            'user_reviews_latest_before_comment' => $user_reviews_latest_before_comment,
            //'recommend_anime_list' => $recommend_anime_list,
        ]);
    }

    /**
     * 更新履歴を表示
     *
     * @return \Illuminate\View\View
     */
    public function showUpdateLog()
    {
        return view('update_log');
    }

    /**
     * プライバシーポリシーを表示
     *
     * @return \Illuminate\View\View
     */
    public function showPrivacyPolicy()
    {
        return view('privacy_policy');
    }

    /**
     * このサイトについての説明を表示
     *
     * @return \Illuminate\View\View
     */
    public function showSiteInformation()
    {
        return view('site_information');
    }
}
