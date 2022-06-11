<?php

namespace App\Repositories;

use App\Models\Cast;
use App\Models\AddCast;
use App\Http\Requests\CastRequest;
use Illuminate\Database\Eloquent\Collection;

class AddCastRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return AddCast::class;
    }

    /**
     * 削除フラグの立っていない声優の追加申請リストを取得
     *
     * @return Collection<int,AddCast> | Collection<null>
     */
    public function getDeleteUnFlag()
    {
        return AddCast::where('delete_flag', 0)->get();
    }

    /**
     * 削除フラグの立っている声優の追加申請リストを降順に取得
     *
     * @return Collection<int,AddCast> | Collection<null>
     */
    public function getDeleteFlagLatest()
    {
        return AddCast::where('delete_flag', 1)->latest('updated_at')->get();
    }

    /**
     * 声優の追加申請データを作成
     *
     * @param CastRequest $request
     * @return void
     */
    public function createAddCastRequest(CastRequest $request)
    {
        AddCast::create($request->validated());
    }

    /**
     * 声優の追加申請に削除フラグを立てて更新
     *
     * @param int $add_cast_id
     * @param CastRequest $request
     * @param Cast $cast
     * @return void
     */
    public function updateAddCastRequest($add_cast_id, CastRequest $request, Cast $cast)
    {
        AddCast::where('id', $add_cast_id)->update(array_merge($request->validated(), [
            'delete_flag' => 1,
            'cast_id' => $cast->id
        ]));
    }
}
