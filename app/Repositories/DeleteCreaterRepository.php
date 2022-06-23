<?php

namespace App\Repositories;

use App\Models\DeleteCreater;
use App\Http\Requests\DeleteRequest;
use Illuminate\Database\Eloquent\Collection;

class DeleteCreaterRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return DeleteCreater::class;
    }

    /**
     * クリエイターの削除申請リストをクリエイターと共に取得
     *
     * @return Collection<int,DeleteCreater> | Collection<null>
     */
    public function getDeleteCreaterRequestListWithCreater()
    {
        return DeleteCreater::with('creater')->get();
    }

    /**
     * クリエイターの削除申請を作成
     *
     * @param int $creater_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteCreaterRequest($creater_id, DeleteRequest $request)
    {
        DeleteCreater::create(['creater_id' => $creater_id, 'remark' => $request->remark]);
    }
}
