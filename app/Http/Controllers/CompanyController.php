<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Services\UserReviewService;

class CompanyController extends Controller
{
    private CompanyService $companyService;
    private UserReviewService $userReviewService;

    public function __construct(
        CompanyService $companyService,
        UserReviewService $userReviewService,
    ) {
        $this->companyService = $companyService;
        $this->userReviewService = $userReviewService;
    }

    /**
     * 会社の情報を表示
     *
     * @param int $company_id
     * @return \Illuminate\View\View
     */
    public function show($company_id)
    {
        $company = $this->companyService->getCompanyWithAnimesWithMyReviews($company_id);
        $user_reviews = $this->userReviewService->getCompanyUserScoreReview($company);
        return view('company', [
            'company' => $company,
            'user_reviews' => $user_reviews,
        ]);
    }
}
