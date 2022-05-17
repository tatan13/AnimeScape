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
     * @param int $anime_id
     * @param string $cast_name
     * @return void
     */
    public function createModifyOccupationRequest($anime_id, string $cast_name)
    {
        ModifyOccupation::create(['anime_id' => $anime_id, 'cast_name' => $cast_name]);
    }

    /**
     * anime_idからアニメの出演声優変更申請データを取得
     *
     * @param int $anime_id
     * @return Collection<int,ModifyOccupation> | Collection<null>
     */
    public function getModifyOccupationsRequestOfAnimeId($anime_id)
    {
        return ModifyOccupation::whereAnimeId($anime_id)->get();
    }

    /**
     * アニメの出演声優変更申請データを削除
     *
     * @param int $anime_id
     * @return void
     */
    public function deleteModifyOccupationsRequestOfAnimeId($anime_id)
    {
        ModifyOccupation::where('anime_id', $anime_id)->delete();
    }
}
