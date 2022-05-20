<?php

namespace App\Repositories;

use App\Models\Cast;
use App\Models\ModifyCast;
use App\Models\Anime;
use App\Http\Requests\ModifyCastRequest;
use Illuminate\Database\Eloquent\Collection;

class CastRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return Cast::class;
    }

    /**
     * cast_idから声優を出演しているアニメとログインユーザーのレビューと共に取得
     *
     * @param int $cast_id
     * @return Cast
     */
    public function getCastWithActAnimesWithMyReviewsById($cast_id)
    {
        return Cast::where('id', $cast_id)->with('actAnimes', function ($query) {
            $query->withMyReviews()->LatestYearCoorMedian();
        })->firstOrFail();
    }

    /**
     * 声優が出演するアニメリストを取得
     *
     * @param Cast $cast
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getActAnimes(Cast $cast)
    {
        return $cast->actAnimes;
    }

    /**
     * 声優を作成
     *
     * @param string $cast_name
     * @return Cast
     */
    public function createByName(string $cast_name)
    {
        return Cast::create(['name' => $cast_name]);
    }

    /**
     * 名前から声優を取得
     *
     * @param string $cast_name
     * @return Cast | null
     */
    public function getCastByName($cast_name)
    {
        return Cast::where('name', $cast_name)->first();
    }

    /**
     * アニメの出演声優を作成
     *
     * @param Cast $cast
     * @param int $anime_id
     * @return void
     */
    public function createOccupationByCastAndAnimeId(Cast $cast, $anime_id)
    {
        $cast->actAnimes()->attach($anime_id);
    }

    /**
     * 声優を検索して出演アニメとログインユーザーのレビューと共に取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getWithactAnimesWithMyReviewsBySearch($search_word)
    {
        if (is_null($search_word)) {
            return Cast::withActAnimesWithMyReviewsLatestLimit()->paginate(100);
        }
        return Cast::where(Cast::SEARCH_COLUMN, 'like', "%$search_word%")
        ->withActAnimesWithMyReviewsLatestLimit()->paginate(50);
    }

    /**
     * 声優情報変更申請データを作成
     *
     * @param Cast $cast
     * @param ModifyCastRequest $request
     * @return void
     */
    public function createModifyCastRequest(Cast $cast, ModifyCastRequest $request)
    {
        $cast->modifyCasts()->create($request->validated());
    }

    /**
     * 声優情報修正申請データから声優情報を更新
     *
     * @param Cast $cast
     * @param ModifyCastRequest $request
     * @return void
     */
    public function updateInformationByRequest(Cast $cast, ModifyCastRequest $request)
    {
        $cast->update($request->validated());
    }

    /**
     * 声優をmodify_cast_idから取得
     *
     * @param int $modify_cast_id
     * @return Cast
     */
    public function getCastByModifyCastId($modify_cast_id)
    {
        return Cast::whereHas('modifyCasts', function ($query) use ($modify_cast_id) {
            $query->where('id', $modify_cast_id);
        })->firstOrFail();
    }

    /**
     * 声優をdelete_cast_idから取得
     *
     * @param int $delete_cast_id
     * @return Cast
     */
    public function getCastByDeleteCastId($delete_cast_id)
    {
        return Cast::whereHas('deleteCasts', function ($query) use ($delete_cast_id) {
            $query->where('id', $delete_cast_id);
        })->firstOrFail();
    }
}
