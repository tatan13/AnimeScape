<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    const SEX = [
        0 => [ 'label' => '女性' ],
        1 => [ 'label' => '男性' ],
    ];

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

    public function user_reviews()
    {
        return $this->hasMany('App\Models\UserReview');
    }

    public function user_like_users()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_users', 'user_id', 'liked_user_id');
    }

    public function user_liked_users()
    {
        return $this->belongsToMany('App\Models\User', 'user_like_users', 'liked_user_id', 'user_id');
    }

    public function like_casts()
    {
        return $this->belongsToMany('App\Models\Cast', 'user_like_casts', 'user_id', 'cast_id');
    }

    public function isLikeUser($uid)
    {
        return $this->user_like_users()->where('uid', $uid)->exists();

    }
 
    public function isLikedUser($uid)
    {
        return $this->user_liked_users()->where('uid', $uid)->exists();
    }

    public function isLikeCast($cast_id)
    {
        return $this->like_casts()->where('cast_id', $cast_id)->exists();
    }
}
