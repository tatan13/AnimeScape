<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Http\Requests\ModifyCastRequest;
use App\Models\ModifyCast;
use App\Models\Cast;
use Illuminate\Database\Eloquent\Collection;

class ModifyCastRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return ModifyCast::class;
    }

    /**
     * 声優情報変更申請データを作成
     *
     * @param Cast $cast
     * @param ModifyCastRequest $request
     * @return void
     */
    public function createModifyCast(Cast $cast, ModifyCastRequest $request)
    {
        $cast->modifyCasts()->create($request->validated());
    }

    /**
     * 声優の情報修正申請データリストを取得
     *
     * @return Collection<int,ModifyCast> | Collection<null>
     */
    public function getModifyCastListWithCast()
    {
        return ModifyCast::with('cast')->get();
    }

    /**
     * 声優情報修正申請データから声優を取得
     *
     * @param ModifyCast $modify_cast
     * @return Cast
     */
    public function getCast(ModifyCast $modify_cast)
    {
        return $modify_cast->cast;
    }

    /**
     * 声優情報修正申請データを削除
     *
     * @param ModifyCast $modify_cast
     * @return void
     */
    public function delete(ModifyCast $modify_cast)
    {
        $modify_cast->delete();
    }
}
