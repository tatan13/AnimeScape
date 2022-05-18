<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeleteCast extends Model
{
    use HasFactory;

    protected $fillable = [
        'cast_id',
        'remark',
    ];

    /**
     * 声優の取得
     *
     * @return BelongsTo
     */
    public function cast()
    {
        return $this->belongsTo('App\Models\Cast');
    }
}
