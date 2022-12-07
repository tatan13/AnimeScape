<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'anime_id',
        'user_id',
        'tag_id',
        'score',
        'comment',
    ];

    /**
     * アニメの取得
     *
     * @return BelongsTo
     */
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }

    /**
     * ユーザーの取得
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * タグの取得
     *
     * @return BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }
}
