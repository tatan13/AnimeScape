<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Anime;

class AddAnime extends Model
{
    use HasFactory;

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
        'company1',
        'company2',
        'company3',
        'media_category',
        'summary',
        'd_anime_store_id',
        'amazon_prime_video_id',
        'fod_id',
        'unext_id',
        'abema_id',
        'disney_plus_id',
        'delete_flag',
        'remark',
    ];

    /**
     * クールをラベルに変換
     *
     * @return string
     */
    public function getCoorLabelAttribute()
    {
        $coor = $this->attributes['coor'];

        if (!isset(ANIME::COOR[$coor])) {
            return '';
        }

        return ANIME::COOR[$coor]['label'];
    }

    /**
     * 放送カテゴリーをラベルに変換
     *
     * @return string
     */
    public function getMediaCategoryLabelAttribute()
    {
        $media_category = $this->attributes['media_category'];

        if (!isset(ANIME::MEDIA_CATEGORY[$media_category])) {
            return '';
        }

        return ANIME::MEDIA_CATEGORY[$media_category]['label'];
    }

    /**
     * アニメを取得
     *
     * @return BelongsTo
     */
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }
}
