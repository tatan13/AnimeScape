<?php

namespace App\Services;

use App\Models\Cast;
use App\Models\Anime;
use App\Models\User;
use App\Repositories\CastRepository;
use App\Repositories\AnimeRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\ReviewRequest;
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
    public function getCastWithActAnimesWithCompaniesAndWithMyReviews($cast_id)
    {
        return $this->castRepository->getCastWithActAnimesWithCompaniesAndWithMyReviewsById($cast_id);
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
}
