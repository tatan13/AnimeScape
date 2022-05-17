<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddAnime extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_short',
        'year',
        'coor',
        'public_url',
        'twitter',
        'hash_tag',
        'sequel',
        'company',
        'city_name',
    ];

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
