<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private SearchService $searchService;

    public function __construct(
        SearchService $searchService,
    ) {
        $this->searchService = $searchService;
    }

    /**
     * 要求カテゴリーの検索結果を表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $search_results = $this->searchService->getSearchResult($request);
        return view('search', [
            'search_word' => $request->search_word,
            'search_results' => $search_results,
            'category' => $request->category,
        ]);
    }
}
