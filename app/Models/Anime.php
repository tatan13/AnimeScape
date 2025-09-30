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
    use \Kyslik\ColumnSortable\Sortable;

    public const WINTER = 1;
    public const SPRING = 2;
    public const SUMMER = 3;
    public const AUTUMN = 4;

    public const NOW_YEAR = 2025;
    public const NOW_COOR = self::AUTUMN;

    public const SEARCH_COLUMN = 'title';

    public const TYPE_MEDIAN = 'median';
    public const TYPE_AVERAGE = 'average';
    public const TYPE_COUNT = 'count';

    public const TYPE_TV = 1;
    public const TYPE_MOVIE = 2;
    public const TYPE_OVA = 3;
    public const TYPE_STREAMING = 4;

    public const COOR = [
        self::WINTER => [ 'label' => '冬' ],
        self::SPRING => [ 'label' => '春' ],
        self::SUMMER => [ 'label' => '夏' ],
        self::AUTUMN => [ 'label' => '秋' ],
    ];

    public const CATEGORY = [
        self::TYPE_MEDIAN => ['label' => '中央値' ],
        self::TYPE_AVERAGE => ['label' => '平均値' ],
        self::TYPE_COUNT => ['label' => '得点数' ],
    ];

    public const MEDIA_CATEGORY = [
        0 => ['label' => '' ],
        self::TYPE_TV => ['label' => 'TVアニメ' ],
        self::TYPE_MOVIE => ['label' => 'アニメ映画' ],
        self::TYPE_OVA => ['label' => 'OVAアニメ' ],
        self::TYPE_STREAMING => ['label' => '配信' ],
    ];

    protected $fillable = [
        'title',
        'title_short',
        'furigana',
        'year',
        'coor',
        'number_of_episode',
        'public_url',
        'twitter',
        'hash_tag',
        'city_name',
        'media_category',
        'summary',
        's_id',
        'd_anime_store_id',
        'amazon_prime_video_id',
        'fod_id',
        'unext_id',
        'abema_id',
        'disney_plus_id',
    ];

    protected $sortable = [
        'title',
        'number_of_episode',
        'median',
        'average',
        'stdev',
        'count',
        'before_median',
        'before_average',
        'before_stdev',
        'before_count',
    ];

    /**
     * 年、クールでマルチソート
     *
     */
    public function unionYearCoorSortable($query, $direction)
    {
        return $query->orderBy('year', $direction)->orderBy('coor', $direction);
    }

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
     * 放送カテゴリーをラベルに変換
     *
     * @return string
     */
    public function getMediaCategoryLabelAttribute()
    {
        $media_category = $this->attributes['media_category'];

        if (!isset(self::MEDIA_CATEGORY[$media_category])) {
            return '';
        }

        return self::MEDIA_CATEGORY[$media_category]['label'];
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
     * タグを取得
     *
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'tag_reviews', 'anime_id', 'tag_id')
                    ->withTimestamps();
    }

    /**
     * 商品を取得
     *
     * @return BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany('App\Models\Item')->withTimestamps();
    }

    /**
     * クリエイターを取得
     *
     * @return BelongsToMany
     */
    public function creaters()
    {
        return $this->belongsToMany('App\Models\Creater', 'anime_creaters', 'anime_id', 'creater_id')->withTimestamps();
    }

    /**
     * 制作会社を取得
     *
     * @return BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany('App\Models\Company')->withTimestamps();
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
     * ユーザーのタグレビューを取得
     *
     * @return HasMany
     */
    public function tagReviews()
    {
        return $this->hasMany('App\Models\TagReview');
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
     * クリエイターの所属アニメ情報を取得
     *
     * @return HasMany
     */
    public function animeCreaters()
    {
        return $this->hasMany('App\Models\AnimeCreater');
    }

    /**
     * アニメの基本情報変更申請を取得
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
        return $this->belongsToMany('App\Models\Cast', 'occupations', 'anime_id', 'cast_id')->withTimestamps();
    }

    /**
     * 出演声優情報変更申請を取得
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

    public function scopeWithMyTagReviews($query)
    {
        if (Auth::check()) {
            return $query->with('tagReviews', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }
        return $query;
    }

    public function scopeLatestCategory($query, $category)
    {
        return $query->latest($category ?? 'median');
    }

    public function scopeLatestYearCoorMedian($query)
    {
        return $query->orderByRaw('year desc, coor desc, median desc');
    }

    public function scopeWithCompanies($query)
    {
        return $query->with('companies');
    }
}
