<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikeCast extends Model
{
    use HasFactory;

    /**
     * ユーザーの取得
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 声優の取得
     */
    public function cast()
    {
        return $this->belongsTo('App\Models\Cast');
    }
}
