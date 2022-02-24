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
            return redirect('index');
        }

        $occupations = $cast->occupations()->get();

        return view('cast', [
            'cast' => $cast,
            'occupations' => $occupations,
        ]);
    }

    public function like($id)
    {
        $cast = Cast::where('id', $id)->first();

        if(!isset($cast)){
            return redirect('index');
        }

        if(Auth::check()){
            $user_like_cast = Auth::user()->like_casts()->where('cast_id', $id)->first();
            if(!isset($user_like_cast)){
                $user_like_cast = new UserLikeCast();
                $user_like_cast->cast_id = $id;
                Auth::user()->like_casts()->save($user_like_cast);
            }
        }

        return redirect(route('cast',['id' => $id]));
    }

    public function dislike($id)
    {
        $cast = Cast::where('id', $id)->first();

        if(!isset($cast)){
            return redirect('index');
        }

        if(Auth::check()){
            $user_like_cast = Auth::user()->like_casts()->where('cast_id', $id)->first();
            if(isset($user_like_cast)){
                $user_like_cast->delete();
            }
        }

        return redirect(route('cast',['id' => $id]));
    }
}