<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Anime;
use App\Models\User;
use App\Models\UserReview;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests\AnimeRequest;
use Illuminate\Support\Facades\DB;

class CompanyRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return Company::class;
    }

    /**
     * 名前から会社を取得
     *
     * @param string $company_name
     * @return Company | null
     */
    public function getByName($company_name)
    {
        return Company::where('name', $company_name)->first();
    }

    /**
     * 名前から会社を作成
     *
     * @param string $company_name
     * @return Company
     */
    public function createByName(string $company_name)
    {
        return Company::create(['name' => $company_name]);
    }

    /**
     * company_idから会社を制作しているアニメとログインユーザーのレビューと共に取得
     *
     * @param int $company_id
     * @return Company
     */
    public function getCompanyWithAnimesWithMyReviewsById($company_id)
    {
        return Company::where('id', $company_id)->with('animes', function ($query) {
            $query->withMyReviews()->LatestYearCoorMedian();
        })->firstOrFail();
    }

    /**
     * リクエストに従ってランキングのために会社の統計情報を取得
     *
     * @param Request  $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCompanyStatistics(Request $request)
    {
        $subquery = UserReview::join('animes', 'user_reviews.anime_id', '=', 'animes.id')
        ->join('anime_company', 'user_reviews.anime_id', '=', 'anime_company.anime_id')
        ->whereNotNull('score')->whereYear($request->year)->whereCoor($request->coor)
        ->whereAboveCount($request->count)->whereColumn('companies.id', 'company_id');
        return Company::select(['id', 'name'])
        ->selectSub(function ($q) use ($subquery) {
            $q->select(DB::raw("COUNT(score)"))->from($subquery->select('score'));
        }, 'score_count')
        ->selectSub(function ($q) use ($subquery) {
            $q->select(DB::raw("AVG(score)"))->from($subquery->select('score'));
        }, 'score_average')
        ->selectSub(function ($q) use ($subquery) {
            $q->select(DB::raw("COUNT(DISTINCT(user_id))"))->from($subquery->select('user_id'));
        }, 'score_users_count')
        ->selectSub(function ($q) use ($subquery) {
            $q->select(DB::raw("AVG(score)"))->whereBetween('ROWNUM', [
                DB::raw("score_count * 1.0 / 2"),
                DB::raw("score_count * 1.0 / 2 + 1")
                ])->from($subquery->select(
                    'score',
                    DB::raw("row_number() OVER(PARTITION BY company_id ORDER BY score) AS ROWNUM"),
                    DB::raw("COUNT(1) OVER(PARTITION BY company_id) AS score_count")
                ));
        }, 'score_median')->withCount(['animes' => function ($q) use ($request) {
            $q->whereYear($request->year)->whereCoor($request->coor)->whereAboveCount($request->count);
        }])->latestCategory($request->category)->paginate(100);
    }

    /**
     * 会社をdelete_company_idから取得
     *
     * @param int $delete_company_id
     * @return Company
     */
    public function getCompanyByDeleteCompanyId($delete_company_id)
    {
        return Company::whereHas('deleteCompanies', function ($query) use ($delete_company_id) {
            $query->where('id', $delete_company_id);
        })->firstOrFail();
    }

    /**
     * 会社が制作しているアニメメリストを取得
     *
     * @param Company $company
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimes(Company $company)
    {
        return $company->animes;
    }

    /**
     * 会社を制作しているアニメとログインユーザーのレビューと共に取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getWithAnimesWithMyReviewsBySearch($search_word)
    {
        if (is_null($search_word)) {
            return Company::withAnimesWithMyReviewsLatestLimit()->latest('name')->paginate(50);
        }
        return Company::where(Company::SEARCH_COLUMN, 'like', "%$search_word%")
        ->orWhere('furigana', 'like', "%$search_word%")
        ->withAnimesWithMyReviewsLatestLimit()->latest('name')->paginate(50);
    }

    /**
     * アニメの制作会社を作成
     *
     * @param Anime $anime
     * @param Company $company
     * @return void
     */
    public function createAnimeCompanyByAnimeAndCompany(Anime $anime, Company $company)
    {
        $company->animes()->attach($anime->id);
    }

    /**
     * ユーザーのレビューしたアニメの制作会社を10個取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Company> | Collection<null>
     */
    public function getUserWatchReview10CompanyList(User $user, Request $request)
    {
        return Company::whereHas('animes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        })->with('animes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            })->with('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1)->orderBy('score');
            });
        })->withCount(['animes' => function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        }])->latest('animes_count')->take(10)->get();
    }

    /**
     * ユーザーのレビューしたアニメの制作会社をすべて取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Company> | Collection<null>
     */
    public function getUserWatchReviewAllCompanyList(User $user, Request $request)
    {
        return Company::whereHas('animes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        })->with('animes', function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            })->with('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1)->orderBy('score');
            });
        })->withCount(['animes' => function ($query) use ($user, $request) {
            $query->whereYear($request->year)->whereCoor($request->coor)
            ->whereHas('userReview', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('watch', 1);
            });
        }])->latest('animes_count')->get();
    }
}
