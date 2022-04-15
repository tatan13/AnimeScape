<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Label;
use App\Models\Anime;

class StatisticsController extends Controller
{
    private const CATEGORY = [
        1 => '中央値',
        2 => '平均値',
        3 => 'データ数',
    ];

    private const COOR = [
        1 => '冬',
        2 => '春',
        3 => '夏',
        4 => '秋',
    ];

    /**
     * すべてのアニメのランキングを表示
     *
     * @param Request $request
     * @param int $category
     * @return \Illuminate\View\View
     */
    public function showAll(Request $request, $category)
    {
        $categoryLabel = new Label($category);
        $categoryLabel->setLabel(self::CATEGORY);

        if (empty($request->count)) {
            $datacount = 0;
        } else {
            $datacount = $request->count;
        }
        switch ($category) {
            case array_search('中央値', self::CATEGORY):
                $animes = Anime::where('count', '>=', $datacount)->get()->sortByDesc('median');
                break;
            case array_search('平均値', self::CATEGORY):
                $animes = Anime::where('count', '>=', $datacount)->get()->sortByDesc('average');
                break;
            case array_search('データ数', self::CATEGORY):
                $animes = Anime::where('count', '>=', $datacount)->get()->sortByDesc('count');
                break;
            default:
            abort(404);
        }

        return view('all_statistics', [
            'animes' => $animes,
            'category' => $categoryLabel,
        ]);
    }

    /**
     * 年別のアニメのランキングを表示
     *
     * @param Request $request
     * @param int $category
     * @return \Illuminate\View\View
     */
    public function showYear(Request $request, $category)
    {
        $categoryLabel = new Label($category);
        $categoryLabel->setLabel(self::CATEGORY);

        if (empty($request->count)) {
            $datacount = 0;
        } else {
            $datacount = $request->count;
        }
        switch ($category) {
            case array_search('中央値', self::CATEGORY):
                $animes = Anime::where('year', (int)$request->year)
                ->where('count', '>=', $datacount)->get()->sortByDesc('median');
                break;
            case array_search('平均値', self::CATEGORY):
                $animes = Anime::where('year', (int)$request->year)
                ->where('count', '>=', $datacount)->get()->sortByDesc('average');
                break;
            case array_search('データ数', self::CATEGORY):
                $animes = Anime::where('year', (int)$request->year)
                ->where('count', '>=', $datacount)->get()->sortByDesc('count');
                break;
            default:
            abort(404);
        }

        return view('year_statistics', [
            'animes' => $animes,
            'category' => $categoryLabel,
            'year' => (int)$request->year,
        ]);
    }

    /**
     * クール別のアニメのランキングを表示
     *
     * @param Request $request
     * @param int $category
     * @return \Illuminate\View\View
     */
    public function showCoor(Request $request, $category)
    {
        $categoryLabel = new Label($category);
        $categoryLabel->setLabel(self::CATEGORY);

        $coorLabel = new Label((int)$request->coor);
        $coorLabel->setLabel(self::COOR);

        if (empty($request->count)) {
            $datacount = 0;
        } else {
            $datacount = $request->count;
        }
        switch ($category) {
            case array_search('中央値', self::CATEGORY):
                $animes = Anime::where('coor', (int)$request->coor)->where('year', (int)$request->year)
                ->where('count', '>=', $datacount)->get()->sortByDesc('median');
                break;
            case array_search('平均値', self::CATEGORY):
                $animes = Anime::where('coor', (int)$request->coor)->where('year', (int)$request->year)
                ->where('count', '>=', $datacount)->get()->sortByDesc('average');
                break;
            case array_search('データ数', self::CATEGORY):
                $animes = Anime::where('coor', (int)$request->coor)->where('year', (int)$request->year)
                ->where('count', '>=', $datacount)->get()->sortByDesc('count');
                break;
            default:
            abort(404);
        }

        return view('coor_statistics', [
            'animes' => $animes,
            'category' => $categoryLabel,
            'coor' => $coorLabel,
            'year' => (int)$request->year,
        ]);
    }
}
