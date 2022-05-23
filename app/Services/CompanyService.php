<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Anime;
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
}
