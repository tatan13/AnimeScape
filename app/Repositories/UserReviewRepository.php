<?php

namespace App\Repositories;

use App\Models\UserReview;
use App\Models\Cast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

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
     * ユーザーレビューを感想タイムスタンプ降順に並び替えて取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserReviewListLatestCommentWithAnimeAndUser()
    {
        return UserReview::LatestCountWithAnimeAndUser()->paginate(20);
    }

    /**
     * ユーザーレビューを視聴完了前感想タイムスタンプ降順に並び替えて取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserReviewListLatestBeforeCommentWithAnimeAndUser()
    {
        return UserReview::LatestBeforeCountWithAnimeAndUser()->paginate(20);
    }

    /**
     * ユーザーレビューを感想タイムスタンプ降順に並び替えて7個まで取得
     *
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getUserReviewListLatestCommentLimitWithAnimeAndUser()
    {
        return UserReview::LatestCountWithAnimeAndUser()->take(7)->get();
    }

    /**
     * ユーザーレビューを視聴完了前感想タイムスタンプ降順に並び替えて7個まで取得
     *
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getUserReviewListLatestBeforeCommentLimitWithAnimeAndUser()
    {
        return UserReview::LatestBeforeCountWithAnimeAndUser()->take(7)->get();
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
    public function getUserReviewWithAnimeAndUserNotNullOneWordComment($user_review_id)
    {
        return UserReview::whereNotNull('one_word_comment')->with(['anime', 'user'])->findOrFail($user_review_id);
    }

    /**
     *　アニメ視聴完了前感想表示のために取得
     *
     * @param int $user_review_id
     * @return UserReview
     */
    public function getForAnimeBeforeComment($user_review_id)
    {
        return UserReview::whereNotNull('before_long_comment')->with(['anime', 'user'])->findOrFail($user_review_id);
    }

    /**
     * 声優に紐づく得点の付いたユーザーレビューを降順に取得
     *
     * @param Cast $cast
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getCastUserScoreReview(Cast $cast)
    {
        return UserReview::whereHas('anime', function ($query) use ($cast) {
            $query->whereHas('occupations', function ($q) use ($cast) {
                $q->where('cast_id', $cast->id);
            });
        })->latest('comment_timestamp')->get();
    }
}
