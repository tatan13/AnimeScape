<?php

namespace App\Repositories;

use App\Models\Occupation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class OccupationRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return Occupation::class;
    }

    /**
     * アニメの出演声優情報を削除
     *
     * @param int $anime_id
     * @return void
     */
    public function deleteOccupationsOfAnimeId($anime_id)
    {
        Occupation::where('anime_id', $anime_id)->delete();
    }
}
