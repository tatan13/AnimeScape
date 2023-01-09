<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Creater extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public const SEARCH_COLUMN = 'name';

    protected $fillable = [
        'name',
        'furigana',
        'sex',
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
     * クリエイターの関わったアニメを取得する
     *
     * @return BelongsToMany
     */
    public function animes()
    {
        return $this->belongsToMany('App\Models\Anime', 'anime_creaters', 'creater_id', 'anime_id')->withTimestamps();
    }

    /**
     * クリエイターの関わったアニメの中韓テーブルを取得する
     *
     * @return HasMany
     */
    public function animeCreaters()
    {
        return $this->hasMany('App\Models\AnimeCreater');
    }

    /**
     * クリエイターの基本情報変更申請を取得
     *
     * @return HasMany
     */
    public function modifyCreaters()
    {
        return $this->hasMany('App\Models\ModifyCreater');
    }

    /**
     * クリエイターの削除申請を取得
     *
     * @return HasMany
     */
    public function deleteCreaters()
    {
        return $this->hasMany('App\Models\DeleteCreater');
    }

    /**
     * 引数に指定されたアニメに関係しているか調べる
     *
     * @param int $anime_id
     * @return bool
     */
    public function isRelationAnime($anime_id)
    {
        return $this->animes()->where('anime_id', $anime_id)->exists();
    }

    public function scopeWithAnimesWithCompaniesAndWithMyReviewsLatestLimit($query)
    {
        return $query->with('animes', function ($q) {
            $q->WithCompanies()->withMyReviews()->LatestYearCoorMedian()->take(10);
        });
    }
}
