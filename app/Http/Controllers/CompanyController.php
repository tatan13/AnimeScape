<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    private CompanyService $companyService;

    public function __construct(
        CompanyService $companyService,
    ) {
        $this->companyService = $companyService;
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
        return view('company', [
            'company' => $company,
        ]);
    }
}
