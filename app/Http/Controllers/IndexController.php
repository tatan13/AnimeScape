<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $animes = Anime::where('year', 2022)->where('coor', 1)->get();
        $animes = $animes->sortByDesc('median');
        return view('index', [
            'animes' => $animes,
        ]);
    }
}
