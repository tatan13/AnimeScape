<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModifyAnime extends Model
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
        'summary',
        'd_anime_store_id',
        'amazon_prime_video_id',
        'fod_id',
        'unext_id',
        'abema_id',
        'disney_plus_id',
    ];

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
