<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        self::WINTER => [ 'label' => '冬' ],
        self::SPRING => [ 'label' => '春' ],
        self::SUMMER => [ 'label' => '夏' ],
        self::AUTUMN => [ 'label' => '秋' ],
    ];

    private const CATEGORY = [
        self::TYPE_MEDIAN => ['label' => '中央値' ],
        self::TYPE_AVERAGE => ['label' => '平均値' ],
        self::TYPE_COUNT => ['label' => 'データ数' ],
    ];

    protected $fillable = [
        'title',
        'title_short',
        'year',
        'coor',
        'public_url',
        'twitter',
        'hash_tag',
        'company',
        'city_name',
    ];

    protected $appends = ['year_coor'];

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
     * @param string $category
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
     * 年とクールを結合した値を取得
     *
     * @return int
     */
    public function getYearCoorAttribute()
    {
        return (int)($this->attributes['year'] . $this->attributes['coor']);
    }

    /**
     * レビューユーザーを取得
     *
     * @return BelongsToMany
     */
    public function reviewUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_reviews', 'anime_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * アニメのおすすめデータのユーザーを取得
     *
     * @return BelongsToMany
     */
    public function recommendAnimeUsers()
    {
        return $this->belongsToMany('App\Models\User', 'anime_recommends', 'anime_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * ユーザーのレビューを取得
     *
     * @return HasMany
     */
    public function userReviews()
    {
        return $this->hasMany('App\Models\UserReview');
    }

    /**
     * アニメのおすすめデータを取得
     *
     * @return HasMany
     */
    public function animeRecommends()
    {
        return $this->hasMany('App\Models\AnimeRecommed');
    }

    /**
     * ユーザーのレビューを取得
     *
     * @return HasOne
     */
    public function userReview()
    {
        return $this->hasOne('App\Models\UserReview');
    }

    /**
     * 声優の所属アニメ情報を取得
     *
     * @return HasMany
     */
    public function occupations()
    {
        return $this->hasMany('App\Models\Occupation');
    }

    /**
     * アニメの基本情報修正依頼を取得
     *
     * @return HasMany
     */
    public function modifyAnimes()
    {
        return $this->hasMany('App\Models\ModifyAnime');
    }

    /**
     * 出演している声優を取得
     *
     * @return BelongsToMany
     */
    public function actCasts()
    {
        return $this->belongsToMany('App\Models\Cast', 'occupations', 'anime_id', 'cast_id');
    }

    /**
     * 出演声優情報修正依頼を取得
     *
     * @return HasMany
     */
    public function modifyOccupations()
    {
        return $this->hasMany('App\Models\ModifyOccupation');
    }

    /**
     * 声優が引数に指定されたアニメに出演しているかを調べる
     *
     * @param string $cast_name
     * @return bool
     */
    public function isActCast($cast_name)
    {
        return $this->actCasts()->where('name', $cast_name)->exists();
    }

    /**
     * yearのクエリスコープ
     *
     * @param int $year
     */
    public function scopeWhereYear($query, $year)
    {
        $query->where('year', $year);
    }

    public function scopeWhereBetWeenYear($query, $bottom_year, $top_year)
    {
        $query->whereBetWeen('year', [$bottom_year ?? 0, $top_year ?? 3000]);
    }

    public function scopeWhereCoor($query, $coor)
    {
        $query->where('coor', $coor);
    }

    public function scopeWhereAboveCount($query, $count)
    {
        $query->where('count', '>=', $count ?? 0);
    }

    public function scopeWhereAboveMedian($query, $median)
    {
        $query->where('median', '>=', $median ?? 0);
    }
}
