<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReview extends Model
{
    use HasFactory;

    private const WILL_WATCH = [
        0 => ['label' => '-' ],
        1 => ['label' => '必ず視聴' ],
        2 => ['label' => '多分視聴' ],
        3 => ['label' => '様子見' ],
    ];

    protected $fillable = [
        'anime_id',
        'user_id',
        'score',
        'one_word_comment',
        'long_word_comment',
        'will_watch',
        'watch',
        'spoiler',
        'give_up',
        'now_watch',
        'number_of_interesting_episode',
        'watch_timestamp',
        'comment_timestamp',
        'before_score',
        'before_comment',
        'before_score_timestamp',
        'before_comment_timestamp',
        'before_long_comment',
        'before_comment_spoiler',
        'number_of_watched_episode',
    ];

    /**
     * 視聴予定をラベルに変換
     *
     * @return string
     */
    public function getWillWatchLabelAttribute()
    {
        $will_watch = $this->attributes['will_watch'];

        if (!isset(self::WILL_WATCH[$will_watch])) {
            return '';
        }

        return self::WILL_WATCH[$will_watch]['label'];
    }

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

    public function scopeWhereInUserIdAndWhereNotNullScore($query, $users_id)
    {
        $query->whereIn('user_id', $users_id)->whereNotNull('score');
    }

    public function scopeLatestAnimeYearCoorMedian($query)
    {
        return $query->join('animes', 'user_reviews.anime_id', '=', 'animes.id')
        ->select('user_reviews.*', 'animes.*')
        ->orderByRaw('animes.year desc, animes.coor desc, animes.median desc');
    }

    public function scopeLatestCommentWithAnimeAndUser($query)
    {
        return $query->with(['anime', 'user'])->whereNotNull('one_word_comment')->orWhereNotNull('long_word_comment')
        ->latest('comment_timestamp');
    }

    public function scopeLatestBeforeCommentWithAnimeAndUser($query)
    {
        return $query->with(['anime', 'user'])->whereNotNull('before_comment')->orWhereNotNull('before_long_comment')
        ->latest('before_comment_timestamp');
    }

    public function scopeWhereYear($query, $year)
    {
        if (is_null($year)) {
            return $query;
        }
        return $query->where('animes.year', $year);
    }

    public function scopeWhereCoor($query, $coor)
    {
        if (is_null($coor)) {
            return $query;
        }
        return $query->where('animes.coor', $coor);
    }

    public function scopeWhereAboveCount($query, $count)
    {
        if (is_null($count)) {
            return $query;
        }
        return $query->where('animes.count', '>=', $count);
    }
}
