<?php

namespace App\Repositories;

use App\Models\AnimeCreater;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AnimeCreaterRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return AnimeCreater::class;
    }

    /**
     * アニメのクリエイター情報を変更
     *
     * @param Request $request
     * @param int $key
     * @return void
     */
    public function modifyAnimeCreaterByRequst($request, $key)
    {
        AnimeCreater::where('id', $request->anime_creater_id[$key])->update([
            'classification' => $request->classification[$key],
            'occupation' => $request->occupation[$key],
            'main_sub' => $request->main_sub[$key],
        ]);
    }

    /**
     * アニメのクリエイターを作成
     *
     * @param int $anime_id
     * @param Request $request
     * @param int $key
     * @return void
     */
    public function createAnimeCreaterByRequest($anime_id, $request, $key)
    {
        AnimeCreater::create([
            'anime_id' => $anime_id,
            'creater_id' => $request->creater_id[$key],
            'classification' => $request->classification[$key],
            'occupation' => $request->occupation[$key],
            'main_sub' => $request->main_sub[$key],
        ]);
    }
}
