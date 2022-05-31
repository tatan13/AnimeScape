<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
use App\Models\UserReview;
use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use Illuminate\Http\Request;
use App\Http\Requests\AnimeRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ReviewsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

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
     * anime_idからアニメを制作会社とともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getWithCompaniesAndMyReviewAndActCastsAndLatestUserReviewsOfAnimeWithUserById($anime_id)
    {
        return Anime::withCompanies()->withMyReviews()->with('actCasts')->with('userReviews', function ($query) {
            $query->with('user')->latest();
        })->findOrFail($anime_id);
    }

    /**
     * anime_idからアニメを制作会社とともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getWithCompaniesById($anime_id)
    {
        return Anime::withCompanies()->findOrFail($anime_id);
    }

    /**
     * anime_idからアニメをログインユーザーのレビューとともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getWithMyReviewById($anime_id)
    {
        return Anime::withMyReviews()->findOrFail($anime_id);
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
     * 今クールのアニメリストを制作会社とログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getNowCoorAnimeListWithCompaniesAndWithMyReviews()
    {
        return Anime::whereYear(Anime::NOW_YEAR)->whereCoor(Anime::NOW_COOR)
        ->withCompanies()->withMyReviews()->sortable()->latest(Anime::TYPE_MEDIAN)->get();
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
     * ユーザーの得点の付いたアニメリストをユーザーレビューと制作会社とともに取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getScoreAnimeListWithCompaniesWithUserReviewOf(User $user)
    {
        return Anime::whereHas('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereNotNull('score');
        })->with('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->withCompanies()->get();
    }

    /**
     * ユーザーの視聴予定アニメリストをユーザーレビューと制作会社とともに取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getWatchAnimeListWithCompaniesWithUserReviewOf(User $user)
    {
        return Anime::whereHas('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('watch', 1);
        })->with('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->withCompanies()->get();
    }

    /**
     * ユーザーの視聴済みアニメリストを放送順にユーザーレビューと制作会社とともに取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestWillWatchAnimeListWithCompaniesWithUserReviewOf(User $user)
    {
        return Anime::whereHas('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereNotIn('will_watch', [0]);
        })->with('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->sortable()->latestYearCoorMedian()->withCompanies()->get();
    }

    /**
     * ユーザーの視聴中アニメリストを放送順にユーザーレビューと制作会社とともに取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestNowWatchAnimeListWithCompaniesOf(User $user)
    {
        return Anime::whereHas('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('now_watch', 1);
        })->sortable()->latestYearCoorMedian()->withCompanies()->get();
    }

    /**
     * ユーザーのギブアップしたアニメリストを放送順にユーザーレビューと制作会社とともに取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestGiveUpAnimeListWithCompaniesOf(User $user)
    {
        return Anime::whereHas('userReview', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('give_up', 1);
        })->sortable()->latestYearCoorMedian()->withCompanies()->get();
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
     * 検索ワードからアニメを制作会社とログインユーザーのレビューと共に取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getWithCompaniesAndWithMyReviewsLatestBySearch($search_word)
    {
        if (is_null($search_word)) {
            return Anime::withCompanies()->withMyReviews()->sortable()->LatestYearCoorMedian()->paginate(500);
        }
        return Anime::where(Anime::SEARCH_COLUMN, 'like', "%$search_word%")
        ->withCompanies()->withMyReviews()->sortable()->LatestYearCoorMedian()->paginate(500);
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
            'now_watch' => $submit_reviews->now_watch[$key],
            'give_up' => $submit_reviews->give_up[$key],
            'number_of_interesting_episode' => $submit_reviews->number_of_interesting_episode[$key],
            'one_word_comment' => $submit_reviews->one_word_comment[$key],
            'watch_timestamp' => $submit_reviews->watch[$key] == true ? Carbon::now() : null,
            'comment_timestamp' => !is_null($submit_reviews->one_word_comment[$key]) ? Carbon::now() : null,
        ]);
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューをReviewsRequestによって更新
     *
     * @param Anime $anime
     * @param UserReview $my_review
     * @param ReviewsRequest $submit_reviews
     * @param int $key
     * @return void
     */
    public function updateMyReviewByReviewsRequest(
        Anime $anime,
        UserReview $my_review,
        ReviewsRequest $submit_reviews,
        $key
    ) {
        $anime->reviewUsers()->updateExistingPivot(Auth::user()->id, [
            'score' => $submit_reviews->score[$key],
            'will_watch' => $submit_reviews->will_watch[$key],
            'watch' => $submit_reviews->watch[$key],
            'now_watch' => $submit_reviews->now_watch[$key],
            'give_up' => $submit_reviews->give_up[$key],
            'number_of_interesting_episode' => $submit_reviews->number_of_interesting_episode[$key],
            'one_word_comment' => $submit_reviews->one_word_comment[$key],
            'watch_timestamp' => $my_review->watch == false &&
            $submit_reviews->watch[$key] == true ?
            Carbon::now() : $my_review->watch_timestamp,
            'comment_timestamp' => is_null($my_review->one_word_comment) &&
            !is_null($submit_reviews->one_word_comment[$key]) ?
            Carbon::now() : $my_review->comment_timestamp,
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
     * アニメの基本情報変更申請データを作成
     *
     * @param Anime $anime
     * @param AnimeRequest $request
     * @return void
     */
    public function createModifyAnimeRequest(Anime $anime, AnimeRequest $request)
    {
        $anime->modifyAnimes()->create($request->validated());
    }

    /**
     * アニメの基本情報変更申請データからアニメの基本情報を更新
     *
     * @param Anime $anime
     * @param AnimeRequest $request
     * @return void
     */
    public function updateInformationByRequest(Anime $anime, AnimeRequest $request)
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
     * アニメリストを制作会社とログインユーザーのレビューと共にリクエストに従って取得
     *
     * @param ReviewsRequest | Request  $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAnimeListWithCompaniesAndWithMyReviewsFor(ReviewsRequest | Request $request)
    {
        return Anime::whereYear($request->year)->whereCoor($request->coor)->whereAboveCount($request->count)
        ->WithCompanies()->withMyReviews()->sortable()->latestCategory($request->category)->paginate(500);
    }

    /**
     * おすすめアニメリストを制作会社とログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getRecommendAnimeListWithCompanies()
    {
        return Auth::user()->recommendAnimes()->withCompanies()->sortable()->latest('recommendation_score')->get();
    }

    /**
     * ログインユーザーがまだ得点入力していない中央値順のTOP5アニメリストを制作会社とログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getTopAnimeListWithCompanies()
    {
        return Anime::whereNotIn('id', Auth::user()->userReviews()->whereNotNull('score')->pluck('anime_id'))
        ->withCompanies()->sortable()->latest('median')->whereAboveCount(2)->take(5)->get();
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

    /**
     * アニメをリクエストに従って追加
     *
     * @param AnimeRequest $request
     * @return Anime
     */
    public function addByRequest(AnimeRequest $request)
    {
        return Anime::create($request->validated());
    }

    /**
     * アニメの制作会社情報を削除
     *
     * @param Anime $anime
     * @return void
     */
    public function deleteAnimeCompanyOfAnime(Anime $anime)
    {
        $anime->companies()->detach();
    }
}
