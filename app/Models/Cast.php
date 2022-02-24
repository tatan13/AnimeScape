<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    public function occupations()
    {
        return $this->hasMany('App\Models\Occupation');
    }

    public function liked_users()
    {
        return $this->hasMany('App\Models\UserLikeCast');
    }
}
