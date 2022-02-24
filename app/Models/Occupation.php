<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    public function cast()
    {
        return $this->belongsTo('App\Models\Cast');
    }

    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }
}
