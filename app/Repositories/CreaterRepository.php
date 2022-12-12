<?php

namespace App\Repositories;

use App\Models\Creater;
use App\Models\ModifyCreater;
use App\Models\Anime;
use App\Http\Requests\CreaterRequest;
use Illuminate\Database\Eloquent\Collection;

class CreaterRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return Creater::class;
    }

    /**
     * creater_idからクリエイターをアニメとログインユーザーのレビューと共に取得
     *
     * @param int $creater_id
     * @return Creater
     */
    public function getCreaterWithAnimesWithCompaniesAndWithMyReviewsById($creater_id)
    {
        return Creater::where('id', $creater_id)->with('animes', function ($query) {
            $query->withCompanies()->withMyReviews()->LatestYearCoorMedian();
        })->firstOrFail();
    }

    /**
     * クリエイターの関わったアニメリストを取得
     *
     * @param Creater $creater
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimes(Creater $creater)
    {
        return $creater->animes;
    }

    /**
     * 名前からクリエイターを作成
     *
     * @param string $creater_name
     * @return Creater
     */
    public function createByName(string $creater_name)
    {
        return Creater::create(['name' => $creater_name]);
    }

    /**
     * 名前からクリエイターを取得
     *
     * @param string $creater_name
     * @return Creater | null
     */
    public function getCreaterByName($creater_name)
    {
        return Creater::where('name', $creater_name)->first();
    }

    /**
     * クリエイターを検索してアニメとログインユーザーのレビューと共に取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getWithAnimesWithCompaniesAndWithMyReviewsBySearch($search_word)
    {
        if (is_null($search_word)) {
            return Creater::withAnimesWithCompaniesAndWithMyReviewsLatestLimit()->paginate(50);
        }
        return Creater::where(Creater::SEARCH_COLUMN, 'like', "%$search_word%")
        ->orWhere('furigana', 'like', "%$search_word%")
        ->withAnimesWithCompaniesAndWithMyReviewsLatestLimit()->paginate(50);
    }

    /**
     * クリエイター情報変更申請データを作成
     *
     * @param Creater $creater
     * @param CreaterRequest $request
     * @return void
     */
    public function createModifyCreaterRequest(Creater $creater, CreaterRequest $request)
    {
        $creater->modifyCreaters()->create($request->validated());
    }

    /**
     * クリエイター情報変更申請データからクリエイター情報を更新
     *
     * @param Creater $creater
     * @param CreaterRequest $request
     * @return void
     */
    public function updateInformationByRequest(Creater $creater, CreaterRequest $request)
    {
        $creater->update($request->validated());
    }

    /**
     * クリエイターをmodify_creater_idから取得
     *
     * @param int $modify_creater_id
     * @return Creater
     */
    public function getCreaterByModifyCreaterId($modify_creater_id)
    {
        return Creater::whereHas('modifyCreaters', function ($query) use ($modify_creater_id) {
            $query->where('id', $modify_creater_id);
        })->firstOrFail();
    }

    /**
     * クリエイターをdelete_creater_idから取得
     *
     * @param int $delete_creater_id
     * @return Creater
     */
    public function getCreaterByDeleteCreaterId($delete_creater_id)
    {
        return Creater::whereHas('deleteCreaters', function ($query) use ($delete_creater_id) {
            $query->where('id', $delete_creater_id);
        })->firstOrFail();
    }

    /**
     * creater_idからクリエイターをapiのために取得
     *
     * @param int $creater_id
     * @return Creater | null
     */
    public function getForApiById($creater_id)
    {
        return Creater::find($creater_id);
    }

    /**
     * クリエイターをリクエストに従って作成
     *
     * @param CreaterRequest $request
     * @return Creater
     */
    public function createByRequest(CreaterRequest $request)
    {
        return Creater::create($request->validated());
    }
}
