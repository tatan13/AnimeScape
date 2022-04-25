<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Models\ModifyOccupation;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ModifyOccupationRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return ModifyOccupation::class;
    }

    /**
     * アニメの出演声優変更申請データを作成
     *
     * @param Anime $anime
     * @param string $cast_name
     * @return void
     */
    public function createModifyOccupation(Anime $anime, string $cast_name)
    {
        $anime->modifyOccupations()->create(['cast_name' => $cast_name]);
    }
}
