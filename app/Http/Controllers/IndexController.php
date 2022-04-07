<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * インデックスページを表示
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $animes = Anime::where('year', 2022)->where('coor', 1)->get();
        $animes = $animes->sortByDesc('median');
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
