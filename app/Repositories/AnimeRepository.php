<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\UserReview;
use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use Illuminate\Http\Request;
use App\Http\Requests\ModifyAnimeRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ReviewsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class AnimeRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return Anime::class;
    }

    /**
     * 今クールのアニメリストを取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getNowCoorAnimeList()
    {
        return Anime::whereYear(2022)->whereCoor(Anime::WINTER)->latest(Anime::TYPE_MEDIAN)->get();
    }

    /**
     * リクエストに従ってすべての期間のアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForAllPeriods(Request $request)
    {
        return Anime::whereAboveCount($request->count)->latest($request->category)->get();
    }

    /**
     * リクエストに従って年別のアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForEachYear(Request $request)
    {
        return  Anime::whereYear($request->year)->whereAboveCount($request->count)
        ->latest($request->category)->get();
    }

    /**
     * リクエストに従ってクール別のアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForEachCoor(Request $request)
    {
        return  Anime::whereCoor($request->coor)->whereYear($request->year)->whereAboveCount($request->count)
        ->latest($request->category)->get();
    }

    /**
     * リクエストに従ってお気に入りユーザー内の統計情報を取得
     *
     * @param array<int> $like_users_and_my_id
     * @return Collection<int,Anime>
     */
    public function getUserAnimeStatisticsWithUserReviewsAndUsers($like_users_and_my_id)
    {
        return Anime::whereHas('userReviews', function ($query) use ($like_users_and_my_id) {
            $query->whereInUserIdAndWhereNotNullScore($like_users_and_my_id);
        })->with('userReviews', function ($query) use ($like_users_and_my_id) {
            $query->whereInUserIdAndWhereNotNullScore($like_users_and_my_id)->with('user', function ($query) {
                $query->select('id', 'name');
            });
        })->select(['id', 'title', 'year', 'coor'])->get();
    }

    /**
     * アニメに出演する声優を取得
     *
     * @param Anime $anime
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getActCasts(Anime $anime)
    {
        return $anime->actCasts;
    }

    /**
     * アニメに紐づくユーザーレビューを取得
     *
     * @param Anime $anime
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getUserReviewsOfAnime(Anime $anime)
    {
        return $anime->userReviews;
    }

    /**
     * アニメに紐づくユーザーレビューを降順に並び替えてユーザーと共に取得
     *
     * @param Anime $anime
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getLatestUserReviewsWithUser(Anime $anime)
    {
        return $anime->userReviews()->with('user')->latest()->get();
    }

    /**
     * アニメからアニメの出演声優変更申請データリストを取得
     *
     * @param Anime $anime
     * @return Collection<int,ModifyOccupation> | Collection<null>
     */
    public function getModifyOccupationListOfAnime(Anime $anime)
    {
        return $anime->modifyOccupations;
    }

    /**
     * アニメの出演声優変更申請データを削除
     *
     * @param Anime $anime
     * @return void
     */
    public function deleteModifyOccupationsOfAnime(Anime $anime)
    {
        $anime->modifyOccupations()->delete();
    }

    /**
     * アニメの出演声優情報を削除
     *
     * @param Anime $anime
     * @return void
     */
    public function deleteOccupations(Anime $anime)
    {
        $anime->occupations()->delete();
    }

    /**
     * ログインユーザーのアニメレビューを取得
     *
     * @param Anime $anime
     * @return UserReview | null
     */
    public function getMyReview(Anime $anime)
    {
        return $anime->userReviews()->where('user_id', Auth::id())->first();
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューを作成
     *
     * @param Anime $anime
     * @param ReviewRequest $submit_review
     * @return void
     */
    public function createMyReview(Anime $anime, ReviewRequest $submit_review)
    {
        $anime->reviewUsers()->attach(Auth::user()->id, $submit_review->validated());
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューを更新
     *
     * @param Anime $anime
     * @param ReviewRequest $submit_review
     * @return void
     */
    public function updateMyReview(Anime $anime, ReviewRequest $submit_review)
    {
        $anime->reviewUsers()->updateExistingPivot(Auth::user()->id, $submit_review->validated());
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューをReviewsRequestによって作成
     *
     * @param Anime $anime
     * @param ReviewsRequest $submit_reviews
     * @param int $key
     * @return void
     */
    public function createMyReviewByReviewsRequest(Anime $anime, ReviewsRequest $submit_reviews, $key)
    {
        $anime->reviewUsers()->attach(Auth::user()->id, [
            'score' => $submit_reviews->score[$key],
            'will_watch' => $submit_reviews->will_watch[$key],
            'watch' => $submit_reviews->watch[$key],
            'one_word_comment' => $submit_reviews->one_word_comment[$key],
            'spoiler' => $submit_reviews->spoiler[$key],
        ]);
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューをReviewsRequestによって更新
     *
     * @param Anime $anime
     * @param ReviewsRequest $submit_reviews
     * @param int $key
     * @return void
     */
    public function updateMyReviewByReviewsRequest(Anime $anime, ReviewsRequest $submit_reviews, $key)
    {
        $anime->reviewUsers()->updateExistingPivot(Auth::user()->id, [
            'score' => $submit_reviews->score[$key],
            'will_watch' => $submit_reviews->will_watch[$key],
            'watch' => $submit_reviews->watch[$key],
            'one_word_comment' => $submit_reviews->one_word_comment[$key],
            'spoiler' => $submit_reviews->spoiler[$key],
        ]);
    }

    /**
     * アニメを更新
     *
     * @param Anime $anime
     * @return void
     */
    public function update(Anime $anime)
    {
        $anime->save();
    }

    /**
     * アニメの基本情報修正申請データからアニメの基本情報を更新
     *
     * @param Anime $anime
     * @param ModifyAnimeRequest $request
     * @return void
     */
    public function updateInformation(Anime $anime, ModifyAnimeRequest $request)
    {
        $anime->update($request->validated());
    }

    /**
     * アニメリストをアニメ出演声優変更申請リストと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListWithModifyOccupationList()
    {
        return Anime::whereHas('modifyOccupations')->with(['occupations', 'modifyOccupations'])->get();
    }

    /**
     * アニメリストをログインユーザーのレビューと共に取得
     *
     * @param ReviewsRequest | Request  $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListWithMyReviewsFor(ReviewsRequest | Request $request)
    {
        return Anime::whereYear($request->year)->whereCoor($request->coor)->with('userReview', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
    }

    /**
     * おすすめアニメリストを取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getRecommendAnimeList()
    {
        return Auth::user()->recommendAnimes()->latest('recommendation_score')->get();
    }

    /**
     * ログインユーザーがまだ得点入力していない中央値順のTOP5アニメリストを取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getTopAnimeList()
    {
        return Anime::whereNotIn('id', Auth::user()->userReviews()->whereNotNull('score')->pluck('anime_id'))
        ->latest('median')->whereAboveCount(1)->take(5)->get();
    }
}
