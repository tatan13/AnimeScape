<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimeRecommend extends Model
{
    use HasFactory;

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
}
