<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifyOccupation extends Model
{
    use HasFactory;

    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }
}
