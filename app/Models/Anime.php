<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Anime extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public const WINTER = 1;
    public const SPRING = 2;
    public const SUMMER = 3;
    public const AUTUMN = 4;

    public const NOW_YEAR = 2022;
    public const NOW_COOR = self::WINTER;

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
     * アニメの削除申請を取得
     *
     * @return HasMany
     */
    public function deleteAnimes()
    {
        return $this->hasMany('App\Models\DeleteAnime');
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
     * アニメの基本情報修正申請を取得
     *
     * @return HasMany
     */
    public function modifyAnimes()
    {
        return $this->hasMany('App\Models\ModifyAnime');
    }

    /**
     * アニメの追加申請を取得
     *
     * @return HasMany
     */
    public function addAnimes()
    {
        return $this->hasMany('App\Models\AddAnime');
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
     * 出演声優情報修正申請を取得
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

    public function scopeWhereYear($query, $year)
    {
        if (is_null($year)) {
            return $query;
        }
        return $query->where('year', $year);
    }

    public function scopeWhereBetWeenYear($query, $bottom_year, $top_year)
    {
        $query->whereBetWeen('year', [$bottom_year ?? 0, $top_year ?? 3000]);
    }

    public function scopeWhereCoor($query, $coor)
    {
        if (is_null($coor)) {
            return $query;
        }
        return $query->where('coor', $coor);
    }

    public function scopeWhereAboveCount($query, $count)
    {
        if (is_null($count)) {
            return $query;
        }
        return $query->where('count', '>=', $count);
    }

    public function scopeWhereAboveMedian($query, $median)
    {
        $query->where('median', '>=', $median ?? 0);
    }

    public function scopeWithMyReviews($query)
    {
        if (Auth::check()) {
            return $query->with('userReview', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }
        return $query;
    }

    public function scopeLatestCategory($query, $category)
    {
        if (is_null($category)) {
            return $query;
        }
        return $query->latest($category);
    }
}
