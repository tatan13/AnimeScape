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
     * idからアニメを取得
     *
     * @param int $id
     * @return Anime
     */
    public function getAnime($id)
    {
        return $this->animeRepository->getById($id);
    }


    /**
     * 今クールのアニメリストを取得
     *
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getNowCoorAnimeList()
    {
        return $this->animeRepository->getNowCoorAnimeList();
    }

    /**
     * リクエストに従ってアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | void | Collection<null>
     */
    public function getAnimeListFor(Request $request)
    {
        if (is_null($request->year) && is_null($request->coor)) {
            return $this->getAnimeListForAllPeriods($request);
        }
        if (!is_null($request->year) && is_null($request->coor)) {
            return $this->getAnimeListForEachYear($request);
        }
        if (!is_null($request->year) && !is_null($request->coor)) {
            return $this->getAnimeListForEachCoor($request);
        }
        if (is_null($request->year) && !is_null($request->coor)) {
            abort(404);
        }
    }

    /**
     * リクエストに従ってすべての期間のアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForAllPeriods(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->animeRepository->getAnimeListForAllPeriods($request);
        }
        abort(404);
    }

    /**
     * リクエストに従って年別のアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForEachYear(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->animeRepository->getAnimeListForEachYear($request);
        }
        abort(404);
    }

    /**
     * リクエストに従ってクール別のアニメリストを取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getAnimeListForEachCoor(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->animeRepository->getAnimeListForEachCoor($request);
        }
        abort(404);
    }

    /**
     * カテゴリーが正しい値か判定
     *
     * @param string $category
     * @return bool
     */
    public function isVelifiedCategory(string $category)
    {
        return  $category === Anime::TYPE_MEDIAN ||
                $category === Anime::TYPE_AVERAGE ||
                $category === Anime::TYPE_COUNT;
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
     * ユーザーの視聴予定アニメリストを放送順にを取得
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
    public function getAnimeListWithModifyOccupationList()
    {
        return $this->animeRepository->getAnimeListWithModifyOccupationList();
    }
}
