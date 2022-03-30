<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    public function occupations()
    {
        return $this->hasMany('App\Models\Occupation');
    }

    public function liked_users()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_casts', 'cast_id', 'user_id');
    }

    public function isLikedUser($user_id)
    {
        return $this->liked_users()->where('user_id', $user_id)->exists();
    }

    public function actAnimes()
    {
        return $this->belongsToMany('App\Models\Anime', 'occupations', 'cast_id', 'anime_id');
    }

    public function isActAnime($id)
    {
        return $this->actAnimes()->where('id', $id)->exists();
    }
}
