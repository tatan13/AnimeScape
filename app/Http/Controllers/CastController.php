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

        if(!isset($cast)){
            return redirect(route('index'));
        }

        $occupations = $cast->occupations()->get();

        return view('cast', [
            'cast' => $cast,
            'occupations' => $occupations,
        ]);
    }

    public function like($id)
    {
        $cast = Cast::find($id);

        if(!isset($cast)){
            return redirect(route('index'));
        }

        if(Auth::check()){
            if(!Auth::user()->isLikeCast($id)){
                Auth::user()->like_casts()->attach($id);
            }
        }

        return;
    }

    public function dislike($id)
    {
        $cast = Cast::find($id);

        if(!isset($cast)){
            return redirect(route('index'));
        }

        if(Auth::check()){
            if(Auth::user()->isLikeCast($id)){
                Auth::user()->like_casts()->detach($id);
            }
        }

        return;
    }
}