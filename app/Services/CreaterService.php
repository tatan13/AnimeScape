<?php

namespace App\Services;

use App\Models\Creater;
use App\Models\Anime;
use App\Models\User;
use App\Repositories\CreaterRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\ReviewRequest;
use Illuminate\Database\Eloquent\Collection;

class CreaterService
{
    private CreaterRepository $createrRepository;
    private UserRepository $userRepository;

    /**
     * コンストラクタ
     *
     * @param CreaterRepository $createrRepository
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(
        CreaterRepository $createrRepository,
        UserRepository $userRepository,
    ) {
        $this->createrRepository = $createrRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * creater_idからクリエイターを取得
     *
     * @param int $creater_id
     * @return Creater
     */
    public function getCreater($creater_id)
    {
        return $this->createrRepository->getById($creater_id);
    }

    /**
     * creater_idからクリエイターをアニメとログインユーザーのレビューと共に取得
     *
     * @param int $creater_id
     * @return Creater
     */
    public function getCreaterWithAnimesWithCompaniesAndWithMyReviews($creater_id)
    {
        return $this->createrRepository->getCreaterWithAnimesWithCompaniesAndWithMyReviewsById($creater_id);
    }

    /**
     * ユーザーのお気に入りクリエイターリストを取得
     *
     * @param User $user
     * @return Collection<int,Creater> | Collection<null>
     */
    public function getLikeCreaterList(User $user)
    {
        return $this->userRepository->getLikeCreaterList($user);
    }

    /**
     * creater_idからクリエイターをapiのために取得
     *
     * @param int $creater_id
     * @return string
     */
    public function getCreaterNameForApi($creater_id)
    {
        return $this->createrRepository->getForApiById($creater_id)->name ?? 'idに対応するクリエイターが存在しません。';
    }

    /**
     * すべてのクリエイターを取得
     *
     * @return Collection<int,Creater> | Collection<null>
     */
    public function getCreaterAll()
    {
        return $this->createrRepository->getAll();
    }
}
