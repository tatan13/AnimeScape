<?php

namespace App\Services;

use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\CreaterRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    private const TYPE_ANIME = 'anime';
    private const TYPE_CAST = 'cast';
    private const TYPE_CREATER = 'creater';
    private const TYPE_COMPANY = 'company';
    private const TYPE_TAG = 'tag';
    private const TYPE_USER = 'user';

    private AnimeRepository $animeRepository;
    private CastRepository $castRepository;
    private CreaterRepository $createrRepository;
    private CompanyRepository $companyRepository;
    private TagRepository $tagRepository;
    private UserRepository $userRepository;

    /**
     * コンストラクタ
     *
     * @param AnimeRepository $animeRepository
     * @param CastRepository $castRepository
     * @param CreaterRepository $createrRepository
     * @param CompanyRepository $companyRepository
     * @param TagRepository $tagRepository
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
        CreaterRepository $createrRepository,
        CompanyRepository $companyRepository,
        TagRepository $tagRepository,
        UserRepository $userRepository,
    ) {
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
        $this->createrRepository = $createrRepository;
        $this->companyRepository = $companyRepository;
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * リクエストに従って検索した結果を取得
     *
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSearchResult(Request $request)
    {
        if ($request->category === self::TYPE_ANIME) {
            return $this->animeRepository->getWithCompaniesAndWithMyReviewsLatestBySearch($request->search_word);
        }
        if ($request->category === self::TYPE_CAST) {
            return $this->castRepository->getWithactAnimesWithCompaniesAndWithMyReviewsBySearch($request->search_word);
        }
        if ($request->category === self::TYPE_CREATER) {
            return $this->createrRepository->getWithAnimesWithCompaniesAndWithMyReviewsBySearch($request->search_word);
        }
        if ($request->category === self::TYPE_COMPANY) {
            return $this->companyRepository->getWithAnimesWithMyReviewsBySearch($request->search_word);
        }
        if ($request->category === self::TYPE_TAG) {
            return $this->tagRepository->getBySearch($request->search_word);
        }
        if ($request->category === self::TYPE_USER) {
            return $this->userRepository->getBySearch($request->search_word);
        }
        abort(404);
    }
}
