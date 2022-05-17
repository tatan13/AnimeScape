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
     * anime_idからアニメを出演声優リストと取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithActCastsById($anime_id)
    {
        return Anime::with('actCasts')->findOrFail($anime_id);
    }

    /**
     * 今クールのアニメリストをログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getNowCoorAnimeListWithMyReviews()
    {
        return Anime::whereYear(Anime::NOW_YEAR)->whereCoor(Anime::NOW_COOR)
        ->withMyReviews()->latest(Anime::TYPE_MEDIAN)->get();
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
     * 検索ワードからアニメをログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null> | array<null>
     */
    public function getWithMyReviewsBySearch($search_word)
    {
        if (is_null($search_word)) {
            return array();
        }
        return Anime::where(Anime::SEARCH_COLUMN, 'like', "%$search_word%")->withMyReviews()->get();
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
     * アニメの基本情報修正申請データを作成
     *
     * @param Anime $anime
     * @param ModifyAnimeRequest $request
     * @return void
     */
    public function createModifyAnimeRequest(Anime $anime, ModifyAnimeRequest $request)
    {
        $anime->modifyAnimes()->create($request->validated());
    }

    /**
     * アニメの基本情報修正申請データからアニメの基本情報を更新
     *
     * @param Anime $anime
     * @param ModifyAnimeRequest $request
     * @return void
     */
    public function updateInformationByRequest(Anime $anime, ModifyAnimeRequest $request)
    {
        $anime->update($request->validated());
    }

    /**
     * アニメリストをアニメ出演声優変更申請リストと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListWithModifyOccupationRequestList()
    {
        return Anime::whereHas('modifyOccupations')->with(['occupations', 'modifyOccupations'])->get();
    }

    /**
     * アニメリストをログインユーザーのレビューと共にリクエストに従って取得
     *
     * @param ReviewsRequest | Request  $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListWithMyReviewsFor(ReviewsRequest | Request $request)
    {
        return Anime::whereYear($request->year)->whereCoor($request->coor)->whereAboveCount($request->count)
        ->withMyReviews()->latestCategory($request->category)->paginate(500);
    }

    /**
     * おすすめアニメリストをログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getRecommendAnimeListWithMyReviews()
    {
        return Auth::user()->recommendAnimes()->withMyReviews()->latest('recommendation_score')->get();
    }

    /**
     * ログインユーザーがまだ得点入力していない中央値順のTOP5アニメリストをログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getTopAnimeListWithMyReviews()
    {
        return Anime::whereNotIn('id', Auth::user()->userReviews()->whereNotNull('score')->pluck('anime_id'))
        ->latest('median')->whereAboveCount(2)->withMyReviews()->take(5)->get();
    }

    /**
     * アニメをmodify_anime_idから取得
     *
     * @param int $modify_anime_id
     * @return Anime
     */
    public function getAnimeByModifyAnimeId($modify_anime_id)
    {
        return Anime::whereHas('modifyAnimes', function ($query) use ($modify_anime_id) {
            $query->where('id', $modify_anime_id);
        })->firstOrFail();
    }

    /**
     * アニメをdelete_anime_idから取得
     *
     * @param int $delete_anime_id
     * @return Anime
     */
    public function getAnimeByDeleteAnimeId($delete_anime_id)
    {
        return Anime::whereHas('deleteAnimes', function ($query) use ($delete_anime_id) {
            $query->where('id', $delete_anime_id);
        })->firstOrFail();
    }
}
