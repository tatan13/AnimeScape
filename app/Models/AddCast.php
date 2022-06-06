<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Cast;

class AddCast extends Model
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
        'blood_type',
        'birth',
        'birthplace',
        'blog_url',
        'delete_flag',
        'cast_id',
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
