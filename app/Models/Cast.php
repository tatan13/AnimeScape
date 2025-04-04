<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Anime;

class Cast extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public const SEARCH_COLUMN = 'name';

    protected $fillable = [
        'name',
        'furigana',
        'sex',
        'office',
        'url',
        'twitter',
        'blog',
        'blood_type',
        'birth',
        'birthplace',
        'blog_url',
    ];

    private const SEX = [
        1 => [ 'label' => '男性' ],
        2 => [ 'label' => '女性' ],
    ];

    /**
     * 性別をラベルに変換
     *
     * @return string
     */
    public function getSexLabelAttribute()
    {
        $sex = $this->attributes['sex'];

        if (!isset(self::SEX[$sex])) {
            return '-';
        }

        return self::SEX[$sex]['label'];
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
     * 被お気に入りユーザーを取得
     *
     * @return BelongsToMany
     */
    public function likedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_casts', 'cast_id', 'user_id')->withTimestamps();
    }

    /**
     * 引数に指定されたユーザーが自身をお気に入り登録しているか調べる
     *
     * @param int $user_id
     * @return bool
     */
    public function isLikedUser($user_id)
    {
        return $this->likedUsers()->where('user_id', $user_id)->exists();
    }

    /**
     * 声優の出演するアニメを取得する
     *
     * @return BelongsToMany
     */
    public function actAnimes()
    {
        return $this->belongsToMany('App\Models\Anime', 'occupations', 'cast_id', 'anime_id')->withTimestamps();
    }

    /**
     * 声優の基本情報変更申請を取得
     *
     * @return HasMany
     */
    public function modifyCasts()
    {
        return $this->hasMany('App\Models\ModifyCast');
    }

    /**
     * 声優の削除申請を取得
     *
     * @return HasMany
     */
    public function deleteCasts()
    {
        return $this->hasMany('App\Models\DeleteCast');
    }

    /**
     * 引数に指定されたアニメに出演しているか調べる
     *
     * @param int $anime_id
     * @return bool
     */
    public function isActAnime($anime_id)
    {
        return $this->actAnimes()->where('anime_id', $anime_id)->exists();
    }

    public function scopeWithActAnimesWithCompaniesAndWithMyReviewsLatestLimit($query)
    {
        return $query->with('actAnimes', function ($q) {
            $q->WithCompanies()->withMyReviews()->LatestYearCoorMedian()->take(10);
        });
    }

    public function scopeLatestCategory($query, $category)
    {
        return $query->latest($category ?? 'score_median');
    }
}
