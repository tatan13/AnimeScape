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

class AnimeService
{
    private $animeRepository;

    public function __construct(
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
        UserRepository $userRepository
    )
    {
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
        $this->userRepository = $userRepository;
    }

    /**
     *
     */
    public function getAnime($id)
    {
        return $this->animeRepository->getById($id);
    }

    /**
     *
     */
    public function getNowCoorAnimeList()
    {
        return $this->animeRepository->getNowCoorAnimeList();
    }

    /**
     *
     */
    public function getAnimeListFor(Request $request)
    {
        if(is_null($request->year) && is_null($request->coor)){
            return $this->getAnimeListForAllPeriods($request);
        }
        if(!is_null($request->year) && is_null($request->coor)){
            return $this->getAnimeListForEachYear($request);
        }
        if(!is_null($request->year) && !is_null($request->coor)){
            return $this->getAnimeListForEachCoor($request);
        }
        if(is_null($request->year) && !is_null($request->coor)){
            abort(404);
        }
    }

    /**
     *
     */
    public function getAnimeListForAllPeriods(Request $request)
    {
        if($this->isVelifiedCategory($request->category))
        {
            return $this->animeRepository->getAnimeListForAllPeriods($request);
        }
        abort(404);
    }

    /**
     *
     */
    public function getAnimeListForEachYear(Request $request)
    {
        if($this->isVelifiedCategory($request->category))
        {
            return $this->animeRepository->getAnimeListForEachYear($request);
        }
        abort(404);
    }

    /**
     *
     */
    public function getAnimeListForEachCoor(Request $request)
    {
        if($this->isVelifiedCategory($request->category))
        {
            return $this->animeRepository->getAnimeListForEachCoor($request);
        }
        abort(404);
    }

    public function isVelifiedCategory(string $category)
    {
        return  $category === Anime::TYPE_MEDIAN ||
                $category === Anime::TYPE_AVERAGE ||
                $category === Anime::TYPE_COUNT;
    }

    /**
     *
     */
    public function getActAnimes(Cast $cast)
    {
        return $this->castRepository->getActAnimes($cast);
    }

    public function getWillWatchAnimeList(User $user)
    {
        return $this->userRepository->getWillWatchAnimeList($user);
    }
}