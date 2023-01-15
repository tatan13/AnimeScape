<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Anime;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'anime_id',
        'category',
        'site_id',
        'url',
        'number',
    ];

    public const TYPE_BD = 1;
    public const TYPE_BD_BOX = 2;
    public const TYPE_DVD = 3;
    public const TYPE_CD = 4;
    public const TYPE_COMIC = 5;
    public const TYPE_NOVEL = 6;

    public const CATEGORY = [
        0 => ['label' => '' ],
        self::TYPE_BD => ['label' => 'BD' ],
        self::TYPE_BD_BOX => ['label' => 'BD-BOX' ],
        self::TYPE_DVD => ['label' => 'DVD' ],
        self::TYPE_CD => ['label' => 'CD' ],
        self::TYPE_COMIC => ['label' => 'コミック' ],
        self::TYPE_NOVEL => ['label' => 'ライトノベル' ],
    ];

    /**
     * カテゴリーをラベルに変換
     *
     * @return string
     */
    public function getCategoryLabelAttribute()
    {
        $category = $this->attributes['category'];

        if (!isset(self::CATEGORY[$category])) {
            return '';
        }

        return self::CATEGORY[$category]['label'];
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
