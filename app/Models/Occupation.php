<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    /**
     * 声優を取得
     */
    public function cast()
    {
        return $this->belongsTo('App\Models\Cast');
    }

    /**
     * アニメを取得
     */
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }
}
