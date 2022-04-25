<?php

namespace App\Repositories;

use App\Models\ModifyAnime;
use App\Models\Anime;
use App\Http\Requests\ModifyAnimeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ModifyAnimeRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return ModifyAnime::class;
    }

    /**
     * アニメの基本情報修正申請データを作成
     *
     * @param Anime $anime
     * @param ModifyAnimeRequest $request
     * @return void
     */
    public function createModifyAnime($anime, ModifyAnimeRequest $request)
    {
        $anime->modifyAnimes()->create($request->validated());
    }

    /**
     * アニメの基本情報修正申請データからアニメを取得
     *
     * @param ModifyAnime $modify_anime
     * @return Anime
     */
    public function getAnime(ModifyAnime $modify_anime)
    {
        return $modify_anime->anime;
    }

    /**
     * アニメの基本情報修正申請データを削除
     *
     * @param ModifyAnime $modify_anime
     * @return void
     */
    public function delete(ModifyAnime $modify_anime)
    {
        $modify_anime->delete();
    }

    /**
     * アニメの基本情報修正申請データリストをアニメと共に取得
     *
     * @return Collection<int,ModifyAnime>  | Collection<null>
     */
    public function getModifyAnimeListWithAnime()
    {
        return ModifyAnime::with('anime')->get();
    }
}
