<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\User;
use App\Models\Cast;
use App\Repositories\CastRepository;
use App\Repositories\AnimeRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\ReviewRequest;

class CastService
{
    private $castRepository;
    private $animeRepository;
    private $userRepository;

    public function __construct(
        CastRepository $castRepository,
        AnimeRepository $animeRepository,
        UserRepository $userRepository,
    )
    {
        $this->castRepository = $castRepository;
        $this->animeRepository = $animeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     *
     */
    public function getCast($id)
    {
        return $this->castRepository->getById($id);
    }

    public function getActCasts(Anime $anime)
    {
        return $this->animeRepository->getActCasts($anime);
    }

    public function getLikeCastList(User $user)
    {
        return $this->userRepository->getLikeCastList($user);
    }
}