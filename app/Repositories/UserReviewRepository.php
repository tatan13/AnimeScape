<?php

namespace App\Repositories;

use App\Models\UserReview;
use Illuminate\Support\Facades\Auth;

class UserReviewRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return UserReview::class;
    }
}
