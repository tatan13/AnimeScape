<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cast;
use App\Models\User;
use App\Models\UserLikeCast;
use Illuminate\Support\Facades\Auth;

class CastController extends Controller
{
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

        return;
    }

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

        return;
    }
}
