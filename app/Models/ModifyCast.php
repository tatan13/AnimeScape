<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModifyCast extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'furigana',
        'sex',
        'office',
        'url',
        'twitter',
        'blog',
        'remark',
    ];

    /**
     * 声優を取得
     *
     * @return BelongsTo
     */
    public function cast()
    {
        return $this->belongsTo('App\Models\Cast');
    }
}
