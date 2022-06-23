<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Cast;

class AddCreater extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'furigana',
        'sex',
        'url',
        'twitter',
        'blog',
        'blood_type',
        'birth',
        'birthplace',
        'blog_url',
        'delete_flag',
        'creater_id',
        'remark',
    ];

    /**
     * クリエイターを取得
     *
     * @return BelongsTo
     */
    public function creater()
    {
        return $this->belongsTo('App\Models\Creater');
    }
}
