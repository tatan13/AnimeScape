<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeleteCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'remark',
    ];

    /**
     * 会社の取得
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
