<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLikeUser extends Model
{
    use HasFactory;

    /**
     * ユーザーの取得
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * 被お気に入りユーザーの取得
     *
     * @return BelongsTo
     */
    public function likedUser()
    {
        return $this->belongsTo('App\Models\User', 'liked_user_id', 'id');
    }
}
