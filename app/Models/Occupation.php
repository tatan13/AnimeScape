<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Occupation extends Model
{
    use HasFactory;

    public const TYPE_MAIN = 3;
    public const TYPE_SUB = 2;
    public const TYPE_OTHERS = 1;

    protected $fillable = [
        'anime_id',
        'cast_id',
        'character',
        'main_sub',
    ];
    /**
     * 声優を取得
     *
     * @return BelongsTo
     */
    public function cast()
    {
        return $this->belongsTo('App\Models\Cast');
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
