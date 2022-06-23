<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\ModifyCreater;
use App\Models\Creater;
use Illuminate\Database\Eloquent\Collection;

class ModifyCreaterRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return ModifyCreater::class;
    }

    /**
     * クリエイターの情報変更申請データリストを取得
     *
     * @return Collection<int,ModifyCreater> | Collection<null>
     */
    public function getModifyCreaterRequestListWithCreater()
    {
        return ModifyCreater::with('creater')->get();
    }

    /**
     * クリエイター情報変更申請データからクリエイターを取得
     *
     * @param ModifyCreater $modify_creater_request
     * @return Creater
     */
    public function getCreater(ModifyCreater $modify_creater_request)
    {
        return $modify_creater_request->creater;
    }
}
