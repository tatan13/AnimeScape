<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    public const SEARCH_COLUMN = 'name';

    /**
     * 声優の所属アニメ情報を取得
     */
    public function occupations()
    {
        return $this->hasMany('App\Models\Occupation');
    }

    /**
     * 被お気に入りユーザーを取得
     */
    public function likedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_casts', 'cast_id', 'user_id');
    }

    /**
     * 引数に指定されたユーザーが自身をお気に入り登録しているか調べる
     * @param int $user_id
     */
    public function isLikedUser($user_id)
    {
        return $this->likedUsers()->where('user_id', $user_id)->exists();
    }

    /**
     * 声優の出演するアニメを取得する
     */
    public function actAnimes()
    {
        return $this->belongsToMany('App\Models\Anime', 'occupations', 'cast_id', 'anime_id');
    }

    /**
     * 引数に指定されたアニメに出演しているか調べる
     * @param int $anime_id
     */
    public function isActAnime($anime_id)
    {
        return $this->actAnimes()->where('anime_id', $anime_id)->exists();
    }
}
