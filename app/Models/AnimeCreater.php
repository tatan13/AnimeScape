<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimeCreater extends Model
{
    use HasFactory;

    public const TYPE_MAIN = 3;
    public const TYPE_SUB = 2;
    public const TYPE_OTHERS = 1;

    public const TYPE_DIRECTOR = 1;
    public const TYPE_SCRIPTWRITER = 2;
    public const TYPE_CHARACTER_DESIGNER = 3;
    public const TYPE_SERIES_CONSTRUCTION = 4;
    public const TYPE_ANIMATION_DIRECTOR = 5;
    public const TYPE_MUSIC = 6;
    public const TYPE_SINGER = 7;
    public const TYPE_ORIGINAL_AUTHOR = 8;
    public const TYPE_CLASSIFICATION_OTHERS = 100;

    public const CLASSIFICATION = [
        self::TYPE_DIRECTOR => ['label' => '監督' ],
        self::TYPE_SCRIPTWRITER => ['label' => '脚本' ],
        self::TYPE_CHARACTER_DESIGNER => ['label' => 'キャラクターデザイン' ],
        self::TYPE_SERIES_CONSTRUCTION => ['label' => 'シリーズ構成' ],
        self::TYPE_ANIMATION_DIRECTOR => ['label' => '作画監督' ],
        self::TYPE_MUSIC => ['label' => '音楽' ],
        self::TYPE_SINGER => ['label' => '歌手' ],
        self::TYPE_ORIGINAL_AUTHOR => ['label' => '原作者' ],
        self::TYPE_CLASSIFICATION_OTHERS => ['label' => 'その他' ],
    ];

    protected $fillable = [
        'anime_id',
        'creater_id',
        'classification',
        'occupation',
        'main_sub',
    ];

    /**
     * 職種をラベルに変換
     *
     * @return string
     */
    public function getClassificationLabelAttribute()
    {
        $classification = $this->attributes['classification'];

        if (!isset(self::CLASSIFICATION[$classification])) {
            return '-';
        }

        return self::CLASSIFICATION[$classification]['label'];
    }

    /**
     * クリエイターを取得
     *
     * @return BelongsTo
     */
    public function creater()
    {
        return $this->belongsTo('App\Models\Creater');
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
