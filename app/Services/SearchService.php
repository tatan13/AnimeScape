<?php

namespace App\Services;

use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\CreaterRepository;
use App\Repositories\UserRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    private const TYPE_ANIME = 'anime';
    private const TYPE_CAST = 'cast';
    private const TYPE_CREATER = 'creater';
    private const TYPE_USER = 'user';
    private const TYPE_COMPANY = 'company';

    private AnimeRepository $animeRepository;
    private CastRepository $castRepository;
    private CreaterRepository $createrRepository;
    private UserRepository $userRepository;
    private CompanyRepository $companyRepository;

    /**
     * コンストラクタ
     *
     * @param AnimeRepository $animeRepository
     * @param CastRepository $castRepository
     * @param CreaterRepository $createrRepository
     * @param UserRepository $userRepository
     * @param CompanyRepository $companyRepository
     * @return void
     */
    public function __construct(
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
        CreaterRepository $createrRepository,
        UserRepository $userRepository,
        CompanyRepository $companyRepository,
    ) {
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
        $this->createrRepository = $createrRepository;
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
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
        if ($request->category === self::TYPE_USER) {
            return $this->userRepository->getBySearch($request->search_word);
        }
        if ($request->category === self::TYPE_COMPANY) {
            return $this->companyRepository->getWithAnimesWithMyReviewsBySearch($request->search_word);
        }
        abort(404);
    }
}
