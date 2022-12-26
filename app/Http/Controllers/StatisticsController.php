<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimeService;
use App\Services\CastService;

class StatisticsController extends Controller
{
    private AnimeService $animeService;
    private CastService $castService;

    public function __construct(
        AnimeService $animeService,
        CastService $castService,
    ) {
        $this->animeService = $animeService;
        $this->castService = $castService;
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
}
