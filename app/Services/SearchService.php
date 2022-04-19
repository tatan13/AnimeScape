<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class SearchService
{

    private const TYPE_ANIME = 'anime';
    private const TYPE_CAST = 'cast';
    private const TYPE_USER = 'user';

    private $animeRepository;
    private $castRepository;
    private $userRepository;

    public function __construct(
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
        UserRepository $userRepository,
    )
    {
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
        $this->userRepository = $userRepository;
    }

    public function getSearchResult(Request $request)
    {
        $search_word = $request->search_word;
        if($request->category === Self::TYPE_ANIME){
            return $this->animeRepository->getBySearch($search_word);
        }
        if($request->category === Self::TYPE_CAST){
            return $this->castRepository->getBySearch($search_word);
        }
        if($request->category === Self::TYPE_USER){
            return $this->userRepository->getBySearch($search_word);
        }
        return array();
    }
}