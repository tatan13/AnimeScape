<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Anime;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    private CompanyRepository $companyRepository;

    /**
     * コンストラクタ
     *
     * @param CompanyRepository $companyRepository
     * @return void
     */
    public function __construct(
        CompanyRepository $companyRepository,
    ) {
        $this->companyRepository = $companyRepository;
    }

    /**
     * company_idから会社を取得
     *
     * @param int $company_id
     * @return Company
     */
    public function getCompany($company_id)
    {
        return $this->companyRepository->getById($company_id);
    }

    /**
     * company_idから会社を制作しているアニメとログインユーザーのレビューと共に取得
     *
     * @param int $company_id
     * @return Company
     */
    public function getCompanyWithAnimesWithMyReviews($company_id)
    {
        return $this->companyRepository->getCompanyWithAnimesWithMyReviewsById($company_id);
    }

    /**
     * ユーザーのレビューしたアニメの制作会社を10個取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Company> | Collection<null>
     */
    public function getUserWatchReview10CompanyList($user, Request $request)
    {
        return $this->companyRepository->getUserWatchReview10CompanyList($user, $request);
    }

    /**
     * ユーザーのレビューしたアニメの制作会社をすべて取得
     *
     * @param User $user
     * @param Request $request
     * @return Collection<int,Company> | Collection<null>
     */
    public function getUserWatchReviewAllCompanyList($user, Request $request)
    {
        return $this->companyRepository->getUserWatchReviewAllCompanyList($user, $request);
    }

    /**
     * リクエストに従ってランキングのために会社の統計情報を取得
     *
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCompanyStatistics(Request $request)
    {
        if ($this->isVelifiedCategory($request->category)) {
            return $this->companyRepository->getCompanyStatistics($request);
        }
        abort(404);
    }

    /**
     * カテゴリーが正しい値か判定
     *
     * @param string | null $category
     * @return bool
     */
    public function isVelifiedCategory(string | null $category)
    {
        return  $category === 'score_median' ||
                $category === 'score_average' ||
                $category === 'animes_count' ||
                $category === 'score_count' ||
                $category === 'score_users_count' ||
                is_null($category);
    }
}
