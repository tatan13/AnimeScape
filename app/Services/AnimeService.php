<?php

namespace App\Services;

use App\Models\Anime;
use App\Repositories\AnimeRepository;
use App\Http\Requests\SubmitScoreRequest;

class AnimeService
{
    private $animeRepository;

    public function __construct(AnimeRepository $animeRepository)
    {
        $this->animeRepository = $animeRepository;
    }

    /**
     *
     */
    public function getAnime(int $id)
    {
        return $this->animeRepository->getById($id);
    }
}