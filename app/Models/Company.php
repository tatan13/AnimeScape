<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public const SEARCH_COLUMN = 'name';

    protected $fillable = [
        'name',
        'furigana',
        'public_url',
        'twitter',
    ];
    /**
     * アニメを取得
     *
     * @return BelongsToMany
     */
    public function animes()
    {
        return $this->belongsToMany('App\Models\Anime');
    }

    public function scopeWithAnimesWithMyReviewsLatestLimit($query)
    {
        return $query->with('animes', function ($q) {
            $q->withMyReviews()->LatestYearCoorMedian()->take(10);
        });
    }
}
