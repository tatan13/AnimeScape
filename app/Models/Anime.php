<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    private const COOR = [
        1 => [ 'label' => '冬' ],
        2 => [ 'label' => '春' ],
        3 => [ 'label' => '夏' ],
        4 => [ 'label' => '秋' ],
    ];

    public function getCoorLabelAttribute()
    {
        $coor = $this->attributes['coor'];

        if (!isset(self::COOR[$coor])) {
            return '';
        }

        return self::COOR[$coor]['label'];
    }

    public function userReviews()
    {
        return $this->hasMany('App\Models\UserReview');
    }

    public function occupations()
    {
        return $this->hasMany('App\Models\Occupation');
    }

    public function modifyAnimes()
    {
        return $this->hasMany('App\Models\ModifyAnime');
    }

    public function actCasts()
    {
        return $this->belongsToMany('App\Models\Cast', 'occupations', 'anime_id', 'cast_id');
    }

    public function isActCast($cast_name)
    {
        return $this->actCasts()->where('name', $cast_name)->exists();
    }

    public function modifyOccupations()
    {
        return $this->hasMany('App\Models\ModifyOccupation');
    }
}
