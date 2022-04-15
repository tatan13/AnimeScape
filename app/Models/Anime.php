<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    private const COOR = [
        1 => [ 'label' => '冬' ],
        2 => [ 'label' => '春' ],
        3 => [ 'label' => '夏' ],
        4 => [ 'label' => '秋' ],
    ];

    /**
     * クールをラベルに変換
     *
     * @return string
     */
    public function getCoorLabelAttribute()
    {
        $coor = $this->attributes['coor'];

        if (!isset(self::COOR[$coor])) {
            return '';
        }

        return self::COOR[$coor]['label'];
    }

    /**
     * ユーザーのレビューを取得
     */
    public function userReviews()
    {
        return $this->hasMany('App\Models\UserReview');
    }

    /**
     * 声優の所属アニメ情報を取得
     */
    public function occupations()
    {
        return $this->hasMany('App\Models\Occupation');
    }

    /**
     * アニメの基本情報修正依頼を取得
     */
    public function modifyAnimes()
    {
        return $this->hasMany('App\Models\ModifyAnime');
    }

    /**
     * 出演している声優を取得
     */
    public function actCasts()
    {
        return $this->belongsToMany('App\Models\Cast', 'occupations', 'anime_id', 'cast_id');
    }

    /**
     * 出演声優情報修正依頼を取得
     */
    public function modifyOccupations()
    {
        return $this->hasMany('App\Models\ModifyOccupation');
    }

    /**
     * 声優が引数に指定されたアニメに出演しているかを調べる
     * 
     * @param string $cast_name
     */
    public function isActCast($cast_name)
    {
        return $this->actCasts()->where('name', $cast_name)->exists();
    }
}
