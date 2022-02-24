<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anime;
use App\Models\Cast;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $search_word = $request->search_word;

        //検索ワードが空文字でなければ検索実行
        if(isset($search_word)){
            switch($request->category){
                case "anime":
                    $search_results = Anime::where('title', 'like', "%$search_word%")->get();
                    break;
                case "cast":
                    $search_results = Cast::where('name', 'like', "%$search_word%")->get();
                    break;
                case "user":
                    $search_results = User::select('uid')->where('uid', 'like', "%$search_word%")->get();
                    break;
                default:
                    $search_results = array();
            }
        }else{
            $search_results = array();
        }
        return view('search', [
            'search_word' => $search_word,
            'search_results' => $search_results,
            'category' => $request->category,
        ]);
    }
}
