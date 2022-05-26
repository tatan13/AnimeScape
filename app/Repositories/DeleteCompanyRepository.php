<?php

namespace App\Repositories;

use App\Models\DeleteCompany;
use App\Http\Requests\DeleteRequest;
use Illuminate\Database\Eloquent\Collection;

class DeleteCompanyRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return DeleteCompany::class;
    }

    /**
     * 会社の削除申請リストをアニメと共に取得
     *
     * @return Collection<int,DeleteCompany> | Collection<null>
     */
    public function getDeleteCompanyRequestListWithCompany()
    {
        return DeleteCompany::with('company')->get();
    }

    /**
     * 会社の削除申請を作成
     *
     * @param int $company_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteCompanyRequest($company_id, DeleteRequest $request)
    {
        DeleteCompany::create(['company_id' => $company_id, 'remark' => $request->remark]);
    }
}
