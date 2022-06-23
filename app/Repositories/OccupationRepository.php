<?php

namespace App\Repositories;

use App\Models\Occupation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
     * アニメの出演声優情報を変更
     *
     * @param Request $request
     * @param int $key
     * @return void
     */
    public function modifyOccupationByRequst($request, $key)
    {
        Occupation::where('id', $request->occupation_id[$key])->update([
            'character' => $request->character[$key],
            'main_sub' => $request->main_sub[$key],
        ]);
    }

    /**
     * アニメの出演声優を作成
     *
     * @param int $anime_id
     * @param Request $request
     * @param int $key
     * @return void
     */
    public function createOccupationByRequest($anime_id, $request, $key)
    {
        Occupation::create([
            'anime_id' => $anime_id,
            'cast_id' => $request->cast_id[$key],
            'character' => $request->character[$key],
            'main_sub' => $request->main_sub[$key],
        ]);
    }
}
