<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifyAnime extends Model
{
    use HasFactory;

    /**
     * アニメを取得
     */
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }
}
