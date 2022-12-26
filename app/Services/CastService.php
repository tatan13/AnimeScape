<?php

namespace App\Services;

use App\Models\Cast;
use App\Models\Anime;
use App\Models\User;
use App\Repositories\CastRepository;
use App\Repositories\AnimeRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class CastService
{
    private CastRepository $castRepository;
    private AnimeRepository $animeRepository;
    private UserRepository $userRepository;

    /**
     * コンストラクタ
     *
     * @param CastRepository $castRepository
     * @param AnimeRepository $animeRepository
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(
        CastRepository $castRepository,
        AnimeRepository $animeRepository,
        UserRepository $userRepository,
    ) {
        $this->castRepository = $castRepository;
        $this->animeRepository = $animeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * cast_idから声優を取得
     *
     * @param int $cast_id
     * @return Cast
     */
    public function getCast($cast_id)
    {
        return $this->castRepository->getById($cast_id);
    }

    /**
     * cast_idから声優を出演しているアニメとログインユーザーのレビューと共に取得
     *
     * @param int $cast_id
     * @return Cast
     */
    public function getCastInformationById($cast_id)
    {
        return $this->castRepository->getCastInformationById($cast_id);
    }

    /**
     * リクエストに従ってランキングのために声優の統計情報を取得
     *
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCastStatistics(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->castRepository->getCastStatistics($request);
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
        return  $category === 'score_median' ||
                $category === 'score_average' ||
                $category === 'act_animes_count' ||
                $category === 'score_count' ||
                $category === 'score_users_count' ||
                $category === 'liked_users_count' ||
                is_null($category);
    }

    /**
     * アニメに出演する声優を取得
     *
     * @param Anime $anime
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getActCasts(Anime $anime)
    {
        return $this->animeRepository->getActCasts($anime);
    }

    /**
     * ユーザーのお気に入り声優リストを取得
     *
     * @param User $user
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getLikeCastList(User $user)
    {
        return $this->userRepository->getLikeCastList($user);
    }

    /**
     * cast_idから声優をapiのために取得
     *
     * @param int $cast_id
     * @return string
     */
    public function getCastNameForApi($cast_id)
    {
        return $this->castRepository->getForApiById($cast_id)->name ?? 'idに対応する声優が存在しません。';
    }

    /**
     * すべての声優を取得
     *
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getCastAll()
    {
        return $this->castRepository->getAll();
    }

    /**
     * ユーザーのレビューしたアニメの声優を10個取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getUserWatchReview10CastList($user, Request $request)
    {
        return $this->castRepository->getUserWatchReview10CastList($user, $request);
    }

    /**
     * ユーザーのレビューしたアニメの声優をすべて取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getUserWatchReviewAllCastList($user, Request $request)
    {
        return $this->castRepository->getUserWatchReviewAllCastList($user, $request);
    }
}
