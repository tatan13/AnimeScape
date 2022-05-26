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

    /**
     * ユーザーのアニメのユーザーレビューをアニメとユーザーとともに取得
     *
     * @param int $user_review_id
     * @return UserReview
     */
    public function getUserReviewWithAnimeAndUser($user_review_id)
    {
        return UserReview::with(['anime', 'user'])->findOrFail($user_review_id);
    }
}
