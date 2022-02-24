<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;
    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
