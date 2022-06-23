<?php

namespace App\Repositories;

use App\Models\Creater;
use App\Models\AddCreater;
use App\Http\Requests\CreaterRequest;
use Illuminate\Database\Eloquent\Collection;

class AddCreaterRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return AddCreater::class;
    }

    /**
     * 削除フラグの立っていないクリエイターの追加申請リストを取得
     *
     * @return Collection<int,AddCreater> | Collection<null>
     */
    public function getDeleteUnFlag()
    {
        return AddCreater::where('delete_flag', 0)->get();
    }

    /**
     * 削除フラグの立っているクリエイターの追加申請リストを降順に取得
     *
     * @return Collection<int,AddCreater> | Collection<null>
     */
    public function getDeleteFlagLatest()
    {
        return AddCreater::where('delete_flag', 1)->latest('updated_at')->get();
    }

    /**
     * クリエイターの追加申請データを作成
     *
     * @param CreaterRequest $request
     * @return void
     */
    public function createAddCreaterRequest(CreaterRequest $request)
    {
        AddCreater::create($request->validated());
    }

    /**
     * クリエイターの追加申請に削除フラグを立てて更新
     *
     * @param int $add_creater_id
     * @param CreaterRequest $request
     * @param Creater $creater
     * @return void
     */
    public function updateAddCreaterRequest($add_creater_id, CreaterRequest $request, Creater $creater)
    {
        AddCreater::where('id', $add_creater_id)->update(array_merge($request->validated(), [
            'delete_flag' => 1,
            'creater_id' => $creater->id
        ]));
    }
}
