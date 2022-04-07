<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cast;
use App\Models\User;
use App\Models\UserLikeCast;
use Illuminate\Support\Facades\Auth;

class CastController extends Controller
{
    /**
     * 声優の情報を表示
     * 
     * @param int $id
     * @return \Illuminate\View\View | \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $cast = Cast::find($id);

        if (!isset($cast)) {
            return redirect(route('index'));
        }

        $act_animes = $cast->actAnimes;

        return view('cast', [
            'cast' => $cast,
            'act_animes' => $act_animes,
        ]);
    }

    /**
     * 声優のお気に入り登録処理
     * 
     * @param int $id
     * @return void | \Illuminate\Http\RedirectResponse
     */
    public function like($id)
    {
        $cast = Cast::find($id);

        if (!isset($cast)) {
            return redirect(route('index'));
        }

        if (Auth::check()) {
            if (!Auth::user()->isLikeCast($id)) {
                Auth::user()->likeCasts()->attach($id);
            }
        }
    }

    /**
     * 声優のお気に入り解除処理
     * 
     * @param int $id
     * @return void | \Illuminate\Http\RedirectResponse
     */
    public function dislike($id)
    {
        $cast = Cast::find($id);

        if (!isset($cast)) {
            return redirect(route('index'));
        }

        if (Auth::check()) {
            if (Auth::user()->isLikeCast($id)) {
                Auth::user()->likeCasts()->detach($id);
            }
        }
    }
}
