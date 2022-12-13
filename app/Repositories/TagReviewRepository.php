<?php

namespace App\Repositories;

use App\Models\TagReview;
use App\Http\Requests\AnimeTagReviewRequest;
use App\Http\Requests\TagReviewRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TagReviewRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return TagReview::class;
    }

    /**
     * タグレビューの得点とコメントを更新
     *
     * @param TagReview $tag_review
     * @param int $score
     * @param string $comment
     * @return void
     */
    public function updateTagReviewByScoreAndComment(TagReview $tag_review, $score, $comment)
    {
        $tag_review->update([
            'score' => $score,
            'comment' => $comment
        ]);
    }

    /**
     * ログインユーザーのアニメとタグに紐づくタグレビューをリクエストによって作成
     *
     * @param array $tag_review_request
     * @return void
     */
    public function createByAnimeTagReviewRequest(array $tag_review_request)
    {
        TagReview::create($tag_review_request);
    }
}
