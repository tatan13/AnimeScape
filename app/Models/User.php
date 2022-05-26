<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public const SEARCH_COLUMN = 'name';

    private const SEX = [
        1 => [ 'label' => '男性' ],
        2 => [ 'label' => '女性' ],
    ];

    /**
     * 性別をラベルに変換
     *
     * @return string
     */
    public function getSexLabelAttribute()
    {
        $sex = $this->attributes['sex'];

        if (!isset(self::SEX[$sex])) {
            return '-';
        }

        return self::SEX[$sex]['label'];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'email',
        'one_comment',
        'twitter',
        'birth',
        'sex',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * レビューしているアニメを取得
     *
     * @return BelongsToMany
     */
    public function reviewAnimes()
    {
        return $this->belongsToMany('App\Models\Anime', 'user_reviews', 'user_id', 'anime_id')
                    ->withTimestamps();
    }

    /**
     * ユーザーのおすすめアニメを取得
     *
     * @return BelongsToMany
     */
    public function recommendAnimes()
    {
        return $this->belongsToMany('App\Models\Anime', 'anime_recommends', 'user_id', 'anime_id')
                    ->withTimestamps();
    }

    /**
     * ユーザーのレビューを取得
     *
     * @return HasMany
     */
    public function userReviews()
    {
        return $this->hasMany('App\Models\UserReview');
    }

    /**
     * ユーザーのおすすめアニメのデータを取得
     *
     * @return HasMany
     */
    public function animeRecommends()
    {
        return $this->hasMany('App\Models\AnimeRecommend');
    }

    /**
     * ユーザーの最新レビューを取得
     *
     * @return HasOne
     */
    public function latestUserReviewUpdatedAt()
    {
        return $this->hasOne('App\Models\UserReview', 'user_id', 'id')->latestOfMany('updated_at');
    }
    /**
     * お気に入りユーザーを取得
     *
     * @return BelongsToMany
     */
    public function userLikeUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_users', 'user_id', 'liked_user_id')
        ->withTimestamps();
    }

    /**
     * 被お気に入りユーザーを取得
     *
     * @return BelongsToMany
     */
    public function userLikedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_users', 'liked_user_id', 'user_id')
        ->withTimestamps();
    }

    /**
     * お気に入り声優を取得
     *
     * @return BelongsToMany
     */
    public function likeCasts()
    {
        return $this->belongsToMany('App\Models\Cast', 'user_like_casts', 'user_id', 'cast_id')->withTimestamps();
    }

    /**
     * 引数に指定されたユーザーをお気に入り登録しているか調べる
     *
     * @param int $user_id
     * @return bool
     */
    public function isLikeUser($user_id)
    {
        return $this->userLikeUsers()->where('liked_user_id', $user_id)->exists();
    }

    /**
     * 引数に指定されたユーザーにお気に入り登録されているか調べる
     *
     * @param int $user_id
     * @return bool
     */
    public function isLikedUser($user_id)
    {
        return $this->userLikedUsers()->where('like_user_id', $user_id)->exists();
    }

    /**
     * 引数に指定された声優をお気に入り登録しているか調べる
     *
     * @param int $cast_id
     * @return bool
     */
    public function isLikeCast($cast_id)
    {
        return $this->likeCasts()->where('cast_id', $cast_id)->exists();
    }

    public function scopeWhereId($query, $user_id)
    {
        $query->where('id', $user_id);
    }
}
