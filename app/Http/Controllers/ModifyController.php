<?php

namespace App\Http\Controllers;
use App\Models\Anime;
use App\Models\ModifyAnime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ModifyController extends Controller
{
    public function modify_anime_show(int $id)
    {
        $anime = Anime::find($id);

        if(!isset($anime)){
            return redirect(route('index'));
        }

        return view('modify_anime', [
            'anime' => $anime,
        ]);
    }

    public function modify_anime_post(Request $request, int $id)
    {
        $modify_anime = new ModifyAnime();

        $modify_anime->anime_id = $id;
        $modify_anime->title = $request->title;
        $modify_anime->title_short = $request->title_short;
        $modify_anime->year = $request->year;
        $modify_anime->coor = $request->coor;
        $modify_anime->public_url = $request->public_url;
        $modify_anime->twitter = $request->twitter;
        $modify_anime->hash_tag = $request->hash_tag;
        $modify_anime->sex = $request->sex;
        $modify_anime->sequel = $request->sequel;
        $modify_anime->company = $request->company;
        $modify_anime->city_name = $request->city_name;
        $modify_anime->save();

        return redirect()->route('modify.anime.show', [
            'id' => $id,
        ])->with('flash_message', '変更申請が完了しました。');
    }
    
    public function modify_anime_update(int $id, Request $request)
    {
        if(Auth::user()->uid != "root"){
            return redirect(route('index'));
        }

        $modify_anime = ModifyAnime::find($id);
        $anime = $modify_anime->anime;
        $anime->title = $request->title;
        $anime->title_short = $request->title_short;
        $anime->year = $request->year;
        $anime->coor = $request->coor;
        $anime->public_url = $request->public_url;
        $anime->twitter = $request->twitter;
        $anime->hash_tag = $request->hash_tag;
        //$anime->sex = $request->sex;
        $anime->sequel = $request->sequel;
        $anime->company = $request->company;
        $anime->city_name = $request->city_name;
        $anime->save();
        $modify_anime->delete();

        return redirect()->route('modify.list.show')->with('flash_message', '変更が完了しました。');
    }

    public function modify_anime_delete(int $id)
    {
        if(Auth::user()->uid != "root"){
            return redirect(route('index'));
        }

        $modify_anime = ModifyAnime::find($id);
        $modify_anime->delete();

        return redirect()->route('modify.list.show')->with('flash_message', '削除が完了しました。');
    }

    public function modify_list_show()
    {
        if(Auth::user()->uid != "root"){
            return redirect(route('index'));
        }

        $modify_animes = ModifyAnime::all();

        return view('modify_list', [
            'modify_animes' => $modify_animes,
        ]);
    }
}
