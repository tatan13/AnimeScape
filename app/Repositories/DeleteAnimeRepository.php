<?php

namespace App\Repositories;

use App\Models\DeleteAnime;
use App\Http\Requests\DeleteRequest;
use Illuminate\Database\Eloquent\Collection;

class DeleteAnimeRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return DeleteAnime::class;
    }

    /**
     * アニメの削除申請リストをアニメと共に取得
     *
     * @return Collection<int,DeleteAnime> | Collection<null>
     */
    public function getDeleteAnimeRequestListWithAnime()
    {
        return DeleteAnime::with('anime')->get();
    }

    /**
     * アニメの削除申請を作成
     *
     * @param int $anime_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteAnimeRequest($anime_id, DeleteRequest $request)
    {
        DeleteAnime::create(['anime_id' => $anime_id, 'remark' => $request->remark]);
    }
}
