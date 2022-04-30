<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimeService;

class StatisticsController extends Controller
{
    private AnimeService $animeService;

    public function __construct(AnimeService $animeService)
    {
        $this->animeService = $animeService;
    }

    /**
     * アニメのランキングを表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $animes = $this->animeService->getAnimeListFor($request);
        return view('anime_statistics', [
            'animes' => $animes,
            'category' => $request->category,
            'year' => $request->year,
            'coor' => $request->coor,
            'count' => (int)$request->count,
        ]);
    }
}
