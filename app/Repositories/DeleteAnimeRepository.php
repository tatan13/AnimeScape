<?php

namespace App\Repositories;

use App\Models\DeleteAnime;
use App\Http\Requests\DeleteAnimeRequest;
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
    public function getDeleteAnimeRequestWithAnime()
    {
        return DeleteAnime::with('anime')->get();
    }

    /**
     * アニメの削除申請を作成
     *
     * @param int $id
     * @param DeleteAnimeRequest $request
     * @return void
     */
    public function createDeleteAnimeRequest($id, DeleteAnimeRequest $request)
    {
        DeleteAnime::create(['anime_id' => $id, 'remark' => $request->remark]);
    }
}
