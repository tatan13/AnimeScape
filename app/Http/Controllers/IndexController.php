<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimeService;

class IndexController extends Controller
{
    private AnimeService $animeService;

    public function __construct(AnimeService $animeService)
    {
        $this->animeService = $animeService;
    }

    /**
     * インデックスページを表示
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $animes = $this->animeService->getNowCoorAnimeListWithMyReviews();
        $recommend_anime_list = $this->animeService->getRecommendAnimeList();
        return view('index', [
            'animes' => $animes,
            'recommend_anime_list' => $recommend_anime_list,
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
