<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimeService;
use App\Services\CastService;
use App\Services\CompanyService;

class StatisticsController extends Controller
{
    private AnimeService $animeService;
    private CastService $castService;
    private CompanyService $companyService;

    public function __construct(
        AnimeService $animeService,
        CastService $castService,
        CompanyService $companyService,
    ) {
        $this->animeService = $animeService;
        $this->castService = $castService;
        $this->companyService = $companyService;
    }

    /**
     * ランキングのインデックスページを表示
     *
     * @return \Illuminate\View\View
     */
    public function showStatisticsIndex()
    {
        return view('statistics_index');
    }

    /**
     * アニメのランキングを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showAnimeStatistics(Request $request)
    {
        $animes = $this->animeService->getAnimeListWithCompaniesAndWithMyReviewsFor($request);
        return view('anime_statistics', [
            'animes' => $animes,
            'category' => $request->category,
            'year' => $request->year,
            'coor' => $request->coor,
            'count' => (int)$request->count,
        ]);
    }

    /**
     * 声優のランキングを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCastStatistics(Request $request)
    {
        $casts = $this->castService->getCastStatistics($request);
        return view('cast_statistics', [
            'casts' => $casts,
            'category' => $request->category,
            'year' => $request->year,
            'coor' => $request->coor,
            'count' => (int)$request->count,
        ]);
    }

    /**
     * 会社のランキングを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCompanyStatistics(Request $request)
    {
        $companies = $this->companyService->getCompanyStatistics($request);
        return view('company_statistics', [
            'companies' => $companies,
            'category' => $request->category,
            'year' => $request->year,
            'coor' => $request->coor,
            'count' => (int)$request->count,
        ]);
    }
}
