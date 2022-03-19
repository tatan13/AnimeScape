<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Label;
use App\Models\Anime;

class StatisticsController extends Controller
{
    const CATEGORY = [
        1 => '中央値',
        2 => '平均値',
        3 => 'データ数',
    ];

    const COOR = [
        1 => '冬',
        2 => '春',
        3 => '夏',
        4 => '秋',
    ];

    public function show_all(Request $request, $category)
    {
        $categoryLabel = new Label($category);
        $categoryLabel->setLabel(self::CATEGORY);

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
            'category' => $categoryLabel,
        ]);
    }
    
    public function show_year(Request $request, $category)
    {
        $categoryLabel = new Label($category);
        $categoryLabel->setLabel(self::CATEGORY);

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
            'category' => $categoryLabel,
            'year' => (integer)$request->year,
        ]);
    }
    
    public function show_coor(Request $request, $category)
    {
        $categoryLabel = new Label($category);
        $categoryLabel->setLabel(self::CATEGORY);

        $coorLabel = new Label((integer)$request->coor);
        $coorLabel->setLabel(self::COOR);
    
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
            'category' => $categoryLabel,
            'coor' => $coorLabel,
            'year' => (integer)$request->year,
        ]);
    }
}
