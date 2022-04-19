<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    public const WINTER = 1;
    public const SPRING = 2;
    public const SUMMER = 3;
    public const AUTUMN = 4;

    public const SEARCH_COLUMN = 'title';

    public const TYPE_MEDIAN = 'median';
    public const TYPE_AVERAGE = 'average';
    public const TYPE_COUNT = 'count';

    private const COOR = [
        Self::WINTER => [ 'label' => '冬' ],
        Self::SPRING => [ 'label' => '春' ],
        Self::SUMMER => [ 'label' => '夏' ],
        Self::AUTUMN => [ 'label' => '秋' ],
    ];

    private const CATEGORY = [
        Self::TYPE_MEDIAN => ['label' => '中央値' ],
        Self::TYPE_AVERAGE => ['label' => '平均値' ],
        Self::TYPE_COUNT => ['label' => 'データ数' ],
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
     * 引数に指定されたクールをラベルに変換
     *
     * @param int $coor
     * @return string
     */
    public static function getCoorLabel($coor)
    {
        if (!isset(self::COOR[$coor])) {
            return '';
        }

        return self::COOR[$coor]['label'];
    }

    /**
     * 引数に指定されたカテゴリーをラベルに変換
     *
     * @param int $coor
     * @return string
     */
    public static function getCategoryLabel($category)
    {
        if (!isset(self::CATEGORY[$category])) {
            return '';
        }

        return self::CATEGORY[$category]['label'];
    }

    /**
     * レビューユーザーを取得
     */
    public function reviewUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_reviews', 'anime_id', 'user_id')
                    ->withTimestamps();
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

    public function scopeWhereYear($query, $year)
    {
        $query->where('year', $year);
    }

    public function scopeWhereCoor($query, $coor)
    {
        $query->where('coor', $coor);
    }

    public function scopeWhereCount($query, $count)
    {
        $query->where('count', '>=', $count ?? 0);
    }
}
