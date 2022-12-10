<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Anime;
use App\Models\User;
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
     * @return Collection<int,Company> | Collection<null>
     */
    public function getUserWatchReview10CompanyList($user)
    {
        return $this->companyRepository->getUserWatchReview10CompanyList($user);
    }

    /**
     * ユーザーのレビューしたアニメの制作会社をすべて取得
     *
     * @param User $user
     * @return Collection<int,Company> | Collection<null>
     */
    public function getUserWatchReviewAllCompanyList($user)
    {
        return $this->companyRepository->getUserWatchReviewAllCompanyList($user);
    }
}
