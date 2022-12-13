<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
use App\Models\Tag;
use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class AnimeService
{
    private AnimeRepository $animeRepository;
    private CastRepository $castRepository;
    private UserRepository $userRepository;

    /**
     * コンストラクタ
     *
     * @param AnimeRepository $animeRepository
     * @param CastRepository $castRepository
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
        UserRepository $userRepository
    ) {
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * anime_idからアニメをに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeById($anime_id)
    {
        return $this->animeRepository->getById($anime_id);
    }

    /**
     * anime_titleからアニメIDを取得
     *
     * @param string $anime_title
     * @return Anime
     */
    public function getAnimeByTitle($anime_title)
    {
        return $this->animeRepository->getByTitle($anime_title);
    }

    /**
     * anime_idからアニメをに取得
     *
     * @param int $anime_id
     * @return Anime | null
     */
    public function getAnimeByIdAllowNull($anime_id)
    {
        return $this->animeRepository->getByIdAllowNull($anime_id);
    }

    /**
     * anime_titleからアニメIDを取得
     *
     * @param string $anime_title
     * @return Anime | null
     */
    public function getAnimeByTitleAllowNull($anime_title)
    {
        return $this->animeRepository->getByTitleAllowNull($anime_title);
    }

    /**
     * anime_idからアニメを制作会社とともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithCompaniesMyReviewOccupationsAnimeCreatersUserReviewsUser($anime_id)
    {
        return $this->animeRepository
        ->getAnimeWithCompaniesMyReviewOccupationsAnimeCreatersUserReviewsUserById($anime_id);
    }

    /**
     * anime_idからアニメを制作会社とともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithCompanies($anime_id)
    {
        return $this->animeRepository->getWithCompaniesById($anime_id);
    }

    /**
     * タグからアニメをタグレビューと制作会社ともに取得
     *
     * @param Tag $tag
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimesByTagWithCompaniesTagReviews(Tag $tag)
    {
        return $this->animeRepository->getAnimesByTagWithCompaniesTagReviews($tag);
    }

    /**
     * anime_idからアニメをログインユーザーのレビューとともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithMyReview($anime_id)
    {
        return $this->animeRepository->getWithMyReviewById($anime_id);
    }

    /**
     * anime_idからアニメをログインユーザーのレビューとともに取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithMyTagReview($anime_id)
    {
        return $this->animeRepository->getWithMyTagReviewById($anime_id);
    }

    /**
     * anime_idからアニメを出演声優リストと取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithActCastsWithOccupationsById($anime_id)
    {
        return $this->animeRepository->getAnimeWithActCastsWithOccupationsById($anime_id);
    }

    /**
     * anime_idからアニメをクリエイターリストと取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithCreatersWithAnimeCreaterById($anime_id)
    {
        return $this->animeRepository->getAnimeWithCreatersWithAnimeCreaterById($anime_id);
    }

    /**
     * 今クールのアニメリストを制作会社とログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getNowCoorAnimeListWithCompaniesAndWithMyReviews()
    {
        return $this->animeRepository->getNowCoorAnimeListWithCompaniesAndWithMyReviews();
    }

    /**
     * リクエストに従ってアニメリストを制作会社とログインユーザーのレビューと共に取得
     *
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAnimeListWithCompaniesAndWithMyReviewsFor(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->animeRepository->getAnimeListWithCompaniesAndWithMyReviewsFor($request);
        }
        abort(404);
    }

    /**
     * カテゴリーが正しい値か判定
     *
     * @param string | null $category
     * @return bool
     */
    public function isVelifiedCategory(string | null $category)
    {
        return  $category === Anime::TYPE_MEDIAN ||
                $category === Anime::TYPE_AVERAGE ||
                $category === Anime::TYPE_COUNT ||
                is_null($category);
    }

    /**
     * リクエストに従ってお気に入りユーザー内の統計情報を取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForStatistics(User $user, Request $request)
    {
        $like_users_and_my_id = $this->userRepository->getLikeUserList($user)->pluck('id')->push($user->id)->toArray();
        $user_anime_statistics = $this->animeRepository
        ->getUserAnimeStatisticsWithUserReviewsAndUsers($like_users_and_my_id)
        ->map(function ($anime) use ($user) {
            $anime['median'] = $anime->userReviews->median('score');
            $anime['count'] = $anime->userReviews->count();
            $anime['isContainMe'] = $anime->userReviews->contains('user_id', $user->id) ? 1 : 0;
            return $anime;
        })->whereBetWeen('year', [$request->bottom_year ?? 0, $request->top_year ?? 3000])
        ->where('count', '>=', $request->count ?? 0)
        ->where('median', '>=', $request->median)
        ->sortByDesc('median');

        return $user_anime_statistics;
    }

    /**
     * 声優が出演するアニメリストを取得
     *
     * @param Cast $cast
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getActAnimes(Cast $cast)
    {
        return $this->castRepository->getActAnimes($cast);
    }

    /**
     * ユーザーの感想をつけたアニメリストを降順のユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestCommentAnimeListWithUserReviewOf(User $user, Request $request)
    {
        return $this->animeRepository->getCommentAnimeListWithUserReviewOf($user, $request)
        ->sortByDesc('userReview.comment_timestamp');
    }

    /**
     * ユーザーの点数をつけたアニメリストを制作会社と降順のユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getScoreAnimeListWithCompaniesWithUserReviewLatestOf(User $user, Request $request)
    {
        return $this->animeRepository->getScoreAnimeListWithCompaniesWithUserReviewOf($user, $request)
        ->sortByDesc('userReview.watch_timestamp');
    }

    /**
     * ユーザーの視聴予定アニメリストを放送順に制作会社とユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestWillWatchAnimeListWithCompaniesWithUserReviewOf(User $user, Request $request)
    {
        return $this->animeRepository->getLatestWillWatchAnimeListWithCompaniesWithUserReviewOf($user, $request);
    }

    /**
     * ユーザーの視聴済みアニメリストを制作会社と降順のユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getWatchAnimeListWithCompaniesWithUserReviewLatestOf(User $user, Request $request)
    {
        return $this->animeRepository->getWatchAnimeListWithCompaniesWithUserReviewOf($user, $request)
        ->sortByDesc('userReview.watch_timestamp');
    }

    /**
     * ユーザーの視聴中アニメリストを放送順に制作会社とユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestNowWatchAnimeList(User $user, Request $request)
    {
        return $this->animeRepository->getLatestNowWatchAnimeList($user, $request);
    }

    /**
     * ユーザーのギブアップしたアニメリストを放送順に制作会社とユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestGiveUpAnimeList(User $user, Request $request)
    {
        return $this->animeRepository->getLatestGiveUpAnimeList($user, $request);
    }


    /**
     * ユーザーの視聴完了前一言感想をつけたアニメリストを降順のユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getLatestBeforeCommentAnimeListWithUserReviewOf(User $user, Request $request)
    {
        return $this->animeRepository->getBeforeCommentAnimeListWithUserReviewOf($user, $request)
        ->sortByDesc('userReview.before_comment_timestamp');
    }

    /**
     * ユーザーの視聴完了前点数をつけたアニメリストを制作会社と降順のユーザーレビューとともにリクエストに従って取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getBeforeScoreAnimeListWithCompaniesWithUserReviewLatestOf(User $user, Request $request)
    {
        return $this->animeRepository->getBeforeScoreAnimeListWithCompaniesWithUserReviewOf($user, $request)
        ->sortByDesc('userReview.before_score_timestamp');
    }

    /**
     * アニメリストをアニメ出演声優変更申請リストと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListWithModifyOccupationRequestList()
    {
        return $this->animeRepository->getAnimeListWithModifyOccupationRequestList();
    }

    /**
     * おすすめアニメリストを制作会社とログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null> | null
     */
    public function getRecommendAnimeListWithCompanies()
    {
        if (Auth::check()) {
            $recommend_anime_list = $this->animeRepository->getRecommendAnimeListWithCompanies();
            return $recommend_anime_list->isEmpty() ?
            $this->animeRepository->getTopAnimeListWithCompanies() : $recommend_anime_list;
        }
        return null;
    }

    /**
     * アニメに紐づく得点の付いたユーザーレビューを取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithUserReviewsWithUserNotNullScoreLatest($anime_id)
    {
        return $this->animeRepository->getAnimeWithUserReviewsWithUserNotNullScoreLatest($anime_id);
    }

    /**
     * アニメに紐づく視聴完了前得点の付いたユーザーレビューを取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithUserReviewsWithUserNotNullBeforeScoreLatest($anime_id)
    {
        return $this->animeRepository->getAnimeWithUserReviewsWithUserNotNullBeforeScoreLatest($anime_id);
    }

    /**
     * 全てのアニメリストを取得
     *
     * @return string
     */
    public function getAllAnimeListByJson()
    {
        return $this->animeRepository->getAll()->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * すべてのアニメを取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeAll()
    {
        return $this->animeRepository->getAll();
    }
}
