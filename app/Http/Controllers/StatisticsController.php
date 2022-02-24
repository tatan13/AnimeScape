<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anime;

class StatisticsController extends Controller
{
    public function show_all(Request $request, $category)
    {
        if(empty($request->count)){
            $datacount = 0;
        }else{
            $datacount = $request->count;
        }
        switch($category){
            case 1:
                $animes = Anime::all()->where('count', '>=', $datacount)->sortByDesc('median');
                break;
            case 2:
                $animes = Anime::all()->where('count', '>=', $datacount)->sortByDesc('average');
                break;
            case 3:
                $animes = Anime::all()->where('count', '>=', $datacount)->sortByDesc('count');
                break;
            default:
                return redirect(route('all_statistics', ['category' => 1]));
        }

        return view('all_statistics',[
            'animes' => $animes,
            'category' => $category,
        ]);
    }
    
    public function show_year(Request $request, $category)
    {
        if(empty($request->count)){
            $datacount = 0;
        }else{
            $datacount = $request->count;
        }
        switch($category){
            case 1:
                $animes = Anime::all()->where('year', (integer)$request->year)->where('count', '>=', $datacount)->sortByDesc('median');
                break;
            case 2:
                $animes = Anime::all()->where('year', (integer)$request->year)->where('count', '>=', $datacount)->sortByDesc('average');
                break;
            case 3:
                $animes = Anime::all()->where('year', (integer)$request->year)->where('count', '>=', $datacount)->sortByDesc('count');
                break;
            default:
                return redirect(route('year_statistics', [
                    'category' => 1,
                    'year' => (integer)$request->year,
                ]));
        }

        return view('year_statistics',[
            'animes' => $animes,
            'category' => $category,
            'year' => (integer)$request->year,
        ]);
    }
    
    public function show_coor(Request $request, $category)
    {
        if(empty($request->count)){
            $datacount = 0;
        }else{
            $datacount = $request->count;
        }
        switch($category){
            case 1:
                $animes = Anime::all()->where('coor', (integer)$request->coor)->where('year', (integer)$request->year)->where('count', '>=', $datacount)->sortByDesc('median');
                break;
            case 2:
                $animes = Anime::all()->where('coor', (integer)$request->coor)->where('year', (integer)$request->year)->where('count', '>=', $datacount)->sortByDesc('average');
                break;
            case 3:
                $animes = Anime::all()->where('coor', (integer)$request->coor)->where('year', (integer)$request->year)->where('count', '>=', $datacount)->sortByDesc('count');
                break;
            default:
                return redirect(route('coor_statistics', [
                    'category' => 1,
                    'coor' => (integer)$request->coor,
                    'year' => (integer)$request->year,
                ]));
        }

        return view('coor_statistics',[
            'animes' => $animes,
            'category' => $category,
            'coor' => (integer)$request->coor,
            'year' => (integer)$request->year,
        ]);
    }
}
