<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    private const TYPE_ANIME = 'anime';
    private const TYPE_CAST = 'cast';
    private const TYPE_USER = 'user';

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
        UserRepository $userRepository,
    ) {
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * リクエストに従って検索した結果を取得
     *
     * @param Request $request
     * @return Collection<int,Anime> | Collection<int,Cast> | Collection<int,User> | Collection<null>
     */
    public function getSearchResult(Request $request)
    {
        $search_word = $request->search_word;
        if ($request->category === self::TYPE_ANIME) {
            return $this->animeRepository->getBySearch($search_word);
        }
        if ($request->category === self::TYPE_CAST) {
            return $this->castRepository->getBySearchWithactAnimes($search_word);
        }
        if ($request->category === self::TYPE_USER) {
            return $this->userRepository->getBySearch($search_word);
        }
        abort(404);
    }
}
