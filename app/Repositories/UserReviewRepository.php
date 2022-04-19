<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Models\UserReview;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;

class UserReviewRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return UserReview::class;
    }
}