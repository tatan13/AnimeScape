<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeleteCreater extends Model
{
    use HasFactory;

    protected $fillable = [
        'creater_id',
        'remark',
    ];

    /**
     * クリエイターの取得
     *
     * @return BelongsTo
     */
    public function creater()
    {
        return $this->belongsTo('App\Models\Creater');
    }
}
