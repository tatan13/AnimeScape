<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
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
     * anime_idからアニメを取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnime($anime_id)
    {
        return $this->animeRepository->getById($anime_id);
    }

    /**
     * anime_idからアニメを出演声優リストと取得
     *
     * @param int $anime_id
     * @return Anime
     */
    public function getAnimeWithActCastsById($anime_id)
    {
        return $this->animeRepository->getAnimeWithActCastsById($anime_id);
    }

    /**
     * 今クールのアニメリストをログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getNowCoorAnimeListWithMyReviews()
    {
        return $this->animeRepository->getNowCoorAnimeListWithMyReviews();
    }

    /**
     * リクエストに従ってアニメリストをログインユーザーのレビューと共に取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | void | Collection<null>
     */
    public function getAnimeListWithMyReviewsFor(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->animeRepository->getAnimeListWithMyReviewsFor($request);
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
     * ユーザーの視聴予定アニメリストを放送順に取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getWillWatchAnimeList(User $user)
    {
        return $this->userRepository->getWillWatchAnimeList($user)->sortByDesc('year_coor');
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
     * おすすめアニメリストをログインユーザーのレビューと共に取得
     *
     * @return Collection<int,Anime> | Collection<null> | null
     */
    public function getRecommendAnimeListWithMyReviews()
    {
        if (Auth::check()) {
            $recommend_anime_list = $this->animeRepository->getRecommendAnimeListWithMyReviews();
            return $recommend_anime_list->isEmpty() ?
            $this->animeRepository->getTopAnimeListWithMyReviews() : $recommend_anime_list;
        }
        return null;
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
}
