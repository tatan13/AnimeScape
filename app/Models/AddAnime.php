<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public const WINTER = 1;
    public const SPRING = 2;
    public const SUMMER = 3;
    public const AUTUMN = 4;

    private const COOR = [
        self::WINTER => [ 'label' => '冬' ],
        self::SPRING => [ 'label' => '春' ],
        self::SUMMER => [ 'label' => '夏' ],
        self::AUTUMN => [ 'label' => '秋' ],
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
     * アニメを取得
     *
     * @return BelongsTo
     */
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }
}
