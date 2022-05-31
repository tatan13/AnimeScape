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
     * ログインユーザーのアニメに紐づくユーザーレビューを作成または更新
     *
     * @param int $my_review_id
     * @param array $update_review
     * @return void
     */
    public function createOrUpdateMyReview($my_review_id, array $update_review)
    {
        UserReview::updateOrCreate(['id' => $my_review_id], $update_review);
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
