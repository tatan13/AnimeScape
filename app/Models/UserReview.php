<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;

    /**
     * アニメの取得
     */
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }

    /**
     * ユーザーの取得
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
