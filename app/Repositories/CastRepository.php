<?php

namespace App\Repositories;

use App\Models\Cast;
use App\Models\User;
use App\Models\ModifyCast;
use App\Models\Anime;
use Illuminate\Http\Request;
use App\Http\Requests\CastRequest;
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
    public function getCastWithActAnimesWithCompaniesAndWithMyReviewsById($cast_id)
    {
        return Cast::where('id', $cast_id)->with('actAnimes', function ($query) {
            $query->withCompanies()->withMyReviews()->LatestYearCoorMedian();
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
     * 名前から声優を作成
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
    public function getWithactAnimesWithCompaniesAndWithMyReviewsBySearch($search_word)
    {
        if (is_null($search_word)) {
            return Cast::withActAnimesWithCompaniesAndWithMyReviewsLatestLimit()->paginate(50);
        }
        return Cast::where(Cast::SEARCH_COLUMN, 'like', "%$search_word%")
        ->withActAnimesWithCompaniesAndWithMyReviewsLatestLimit()->paginate(50);
    }

    /**
     * 声優情報変更申請データを作成
     *
     * @param Cast $cast
     * @param CastRequest $request
     * @return void
     */
    public function createModifyCastRequest(Cast $cast, CastRequest $request)
    {
        $cast->modifyCasts()->create($request->validated());
    }

    /**
     * 声優情報変更申請データから声優情報を更新
     *
     * @param Cast $cast
     * @param CastRequest $request
     * @return void
     */
    public function updateInformationByRequest(Cast $cast, CastRequest $request)
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

    /**
     * cast_idから声優をapiのために取得
     *
     * @param int $cast_id
     * @return Cast | null
     */
    public function getForApiById($cast_id)
    {
        return Cast::find($cast_id);
    }

    /**
     * 声優をリクエストに従って作成
     *
     * @param CastRequest $request
     * @return Cast
     */
    public function createByRequest(CastRequest $request)
    {
        return Cast::create($request->validated());
    }

    /**
     * ユーザーのレビューしたアニメの声優を10個取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getUserWatchReview10CastList(User $user, Request $request)
    {
        return Cast::whereHas('actAnimes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        })->with('actAnimes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            })->with('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1)->orderBy('score');
            });
        })->withCount(['actAnimes' => function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        }])->latest('act_animes_count')->take(10)->get();
    }

    /**
     * ユーザーのレビューしたアニメの声優をすべて取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getUserWatchReviewAllCastList(User $user, Request $request)
    {
        return Cast::whereHas('actAnimes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        })->with('actAnimes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            })->with('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1)->orderBy('score');
            });
        })->withCount(['actAnimes' => function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        }])->latest('act_animes_count')->get();
    }
}
