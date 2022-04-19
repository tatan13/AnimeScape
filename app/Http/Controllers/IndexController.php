<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnimeService;

class IndexController extends Controller
{
    private $animeService;

    public function __construct(AnimeService $animeService)
    {
        $this->animeService = $animeService;
    }
    /**
     * インデックスページを表示
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $animes = $this->animeService->getNowCoorAnimeList();
        return view('index', [
            'animes' => $animes,
        ]);
    }

    /**
     * 更新履歴を表示
     *
     * @return \Illuminate\View\View
     */
    public function updateLog()
    {
        return view('update_log');
    }
}
