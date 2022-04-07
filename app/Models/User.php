<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    private const SEX = [
        0 => [ 'label' => '女性' ],
        1 => [ 'label' => '男性' ],
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
        'uid',
        'password',
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
     * ユーザーのレビューを取得
     */
    public function userReviews()
    {
        return $this->hasMany('App\Models\UserReview');
    }

    /**
     * お気に入りユーザーを取得
     */
    public function userLikeUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_users', 'user_id', 'liked_user_id');
    }

    /**
     * 被お気に入りユーザーを取得
     */
    public function userLikedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_users', 'liked_user_id', 'user_id');
    }

    /**
     * お気に入り声優を取得
     */
    public function likeCasts()
    {
        return $this->belongsToMany('App\Models\Cast', 'user_like_casts', 'user_id', 'cast_id');
    }

    /**
     * 引数に指定されたユーザーをお気に入り登録しているか調べる
     * @param string $uid
     */
    public function isLikeUser($uid)
    {
        return $this->userLikeUsers()->where('uid', $uid)->exists();
    }

    /**
     * 引数に指定されたユーザーにお気に入り登録されているか調べる
     * @param string $uid
     */
    public function isLikedUser($uid)
    {
        return $this->userLikedUsers()->where('uid', $uid)->exists();
    }

    /**
     * 引数に指定された声優をお気に入り登録しているか調べる
     * @param int $cast_id
     */
    public function isLikeCast($cast_id)
    {
        return $this->likeCasts()->where('cast_id', $cast_id)->exists();
    }
}
