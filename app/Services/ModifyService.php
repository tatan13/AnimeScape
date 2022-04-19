<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use App\Models\UserReview;
use App\Http\Requests\ModifyAnimeRequest;
use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\ModifyAnimeRepository;
use App\Repositories\ModifyOccupationRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ModifyService
{
    private $modifyAnimeRepository;
    private $modifyOccupationRepository;
    private $animeRepository;
    private $castRepository;

    public function __construct(
        ModifyAnimeRepository $modifyAnimeRepository,
        ModifyOccupationRepository $modifyOccupationRepository,
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
    )
    {
        $this->modifyAnimeRepository = $modifyAnimeRepository;
        $this->modifyOccupationRepository = $modifyOccupationRepository;
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
    }

    /**
     *
     */
    public function getModifyAnime($id)
    {
        return $this->modifyAnimeRepository->getById($id);
    }

    /**
     *
     */
    public function createModifyAnime(Anime $anime, ModifyAnimeRequest $request)
    {
        $this->modifyAnimeRepository->createModifyAnime($anime, $request);
    }

    /**
     *
     */
    public function updateAnimeInfoBy(ModifyAnime $modify_anime, ModifyAnimeRequest $request)
    {
        $anime = $this->modifyAnimeRepository->getAnime($modify_anime);
        $anime->title = $request->title;
        $anime->title_short = $request->title_short;
        $anime->year = $request->year;
        $anime->coor = $request->coor;
        $anime->public_url = $request->public_url;
        $anime->twitter = $request->twitter;
        $anime->hash_tag = $request->hash_tag;
        $anime->sequel = $request->sequel;
        $anime->company = $request->company;
        $anime->city_name = $request->city_name;
        DB::transaction(function () use ($anime, $modify_anime) {
            $this->animeRepository->update($anime);
            $this->modifyAnimeRepository->delete($modify_anime);
        });
    }

    /**
     *
     */
    public function deleteModifyAnime(ModifyAnime $modify_anime)
    {
        $this->modifyAnimeRepository->delete($modify_anime);
    }

    /**
     *
     */
    public function getModifyAnimeList()
    {
        return $this->modifyAnimeRepository->getAll();
    }

    /**
     *
     */
    public function getModifyOccupationsList()
    {
        return $this->modifyOccupationRepository->getModifyOccupationsList();
    }

    /**
     *
     */
    public function getModifyOccupationListOfAnime(Anime $anime)
    {
        return $this->animeRepository->getModifyOccupationListOfAnime($anime);
    }

    /**
     *
     */
    public function deleteModifyOccupationsOfAnime(Anime $anime)
    {
        $this->animeRepository->deleteModifyOccupationsOfAnime($anime);
    }

    /**
     *
     */
    public function createModifyOccupations(Anime $anime, Request $request)
    {
        $req_casts = $this->filterRequest($request);
        $modify_occupation_list = $this->animeRepository->getModifyOccupationListOfAnime($anime);
        foreach ($req_casts as $req_cast) {
            if (!$this->isContainCastName($modify_occupation_list, $req_cast)) {
                $this->modifyOccupationRepository->createModifyOccupation($anime, $req_cast);
            }
        }
    }

    public function updateAnimeCastsInfo(Anime $anime, Request $request)
    {
        $req_casts = $this->filterRequest($request);
        DB::transaction(function () use ($anime, $req_casts) {
            $this->animeRepository->deleteOccupations($anime);
            foreach ($req_casts as $req_cast) {
                $cast = $this->castRepository->getCastByName($req_cast);
                if (!isset($cast)) {
                    $cast = new Cast();
                    $cast->name = $req_cast;
                    $this->castRepository->update($cast);
                }
                $this->castRepository->createOccupation($cast, $anime);
            }
            $this->animeRepository->deleteModifyOccupationsOfAnime($anime);
        });
    }

    public function filterRequest(Request $request)
    {
        $request = $request->except('_token');
        return array_filter($request);
    }

    public function isContainCastName(Collection $list, String $cast_name)
    {
        return $list->contains('cast_name', $cast_name);
    }
}