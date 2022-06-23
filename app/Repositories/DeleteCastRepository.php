<?php

namespace App\Repositories;

use App\Models\DeleteCast;
use App\Http\Requests\DeleteRequest;
use Illuminate\Database\Eloquent\Collection;

class DeleteCastRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return DeleteCast::class;
    }

    /**
     * 声優の削除申請リストを声優と共に取得
     *
     * @return Collection<int,DeleteCast> | Collection<null>
     */
    public function getDeleteCastRequestListWithCast()
    {
        return DeleteCast::with('cast')->get();
    }

    /**
     * 声優の削除申請を作成
     *
     * @param int $cast_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteCastRequest($cast_id, DeleteRequest $request)
    {
        DeleteCast::create(['cast_id' => $cast_id, 'remark' => $request->remark]);
    }
}
