<?php

namespace App\Repositories;

use App\Models\AddAnime;
use App\Http\Requests\AnimeRequest;
use Illuminate\Database\Eloquent\Collection;

class AddAnimeRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return AddAnime::class;
    }

    /**
     * アニメの追加申請データを作成
     *
     * @param AnimeRequest $request
     * @return void
     */
    public function createAddAnimeRequest(AnimeRequest $request)
    {
        AddAnime::create($request->validated());
    }
}
