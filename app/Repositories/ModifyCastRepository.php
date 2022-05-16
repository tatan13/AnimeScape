<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Http\Requests\ModifyCastRequest;
use App\Models\ModifyCast;
use App\Models\Cast;
use Illuminate\Database\Eloquent\Collection;

class ModifyCastRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return ModifyCast::class;
    }

    /**
     * 声優の情報修正申請データリストを取得
     *
     * @return Collection<int,ModifyCast> | Collection<null>
     */
    public function getModifyCastRequestListWithCast()
    {
        return ModifyCast::with('cast')->get();
    }

    /**
     * 声優情報修正申請データから声優を取得
     *
     * @param ModifyCast $modify_cast_request
     * @return Cast
     */
    public function getCast(ModifyCast $modify_cast_request)
    {
        return $modify_cast_request->cast;
    }
}
