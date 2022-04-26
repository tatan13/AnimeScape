<?php

namespace App\Services;

use App\Models\UserReview;
use App\Models\User;
use App\Models\Anime;
use App\Repositories\UserReviewRepository;
use App\Repositories\AnimeRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ReviewsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class UserReviewService
{
    private UserReviewRepository $userReviewRepository;
    private AnimeRepository $animeRepository;

    /**
     * コンストラクタ
     *
     * @param UserReviewRepository $userReviewRepository
     * @param AnimeRepository $animeRepository
     * @return void
     */
    public function __construct(
        UserReviewRepository $userReviewRepository,
        AnimeRepository $animeRepository
    ) {
        $this->userReviewRepository = $userReviewRepository;
        $this->animeRepository = $animeRepository;
    }

    /**
     * idからユーザーレビューを取得
     *
     * @param int $id
     * @return UserReview
     */
    public function getUserReview($id)
    {
        return $this->userReviewRepository->getById($id);
    }

    /**
     * アニメに紐づくユーザーレビューを取得
     *
     * @param Anime $anime
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getUserReviewsOfAnime(Anime $anime)
    {
        return $this->animeRepository->getUserReviewsOfAnime($anime);
    }

    /**
     * アニメに紐づくユーザーレビューを降順に並び替えて取得
     *
     * @param Anime $anime
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getLatestUserReviewsOfAnimeWithUser(Anime $anime)
    {
        return $this->animeRepository->getLatestUserReviewsWithUser($anime);
    }

    /**
     * ログインユーザーのアニメレビューを取得
     *
     * @param Anime $anime
     * @return UserReview | null
     */
    public function getMyReview(Anime $anime)
    {
        if (Auth::check()) {
            $my_review = $this->animeRepository->getMyReview($anime) ?? new UserReview();
        } else {
            $my_review = null;
        }
        return $my_review;
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューを作成または更新
     *
     * @param Anime $anime
     * @param ReviewRequest $submit_score
     * @return void
     */
    public function createOrUpdateMyReview(Anime $anime, ReviewRequest $submit_review)
    {
        $my_review = $this->animeRepository->getMyReview($anime);
        if (is_null($my_review)) {
            DB::transaction(function () use ($anime, $submit_review) {
                $this->animeRepository->createMyReview($anime, $submit_review);
                $this->updateScoreStatistics($anime);
            });
        } else {
            DB::transaction(function () use ($anime, $submit_review) {
                $this->animeRepository->updateMyReview($anime, $submit_review);
                $this->updateScoreStatistics($anime);
            });
        }
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューを複数作成または更新
     *
     * @param ReviewsRequest $submit_reviews
     * @return void
     */
    public function createOrUpdateMyMultipleReview(ReviewsRequest $submit_reviews)
    {
        $anime_list = $this->animeRepository->getAnimeListWithMyReviewsFor($submit_reviews);
        foreach ($submit_reviews->anime_id as $key => $anime_id) {
            if (
                !is_null($submit_reviews->score[$key]) ||
                $submit_reviews->will_watch[$key] == true ||
                $submit_reviews->watch[$key] == true ||
                !is_null($submit_reviews->one_word_comment[$key]) ||
                $submit_reviews->will_watch[$key] == true ||
                $submit_reviews->spoiler[$key] == true
            ) {
                $anime = $anime_list->where('id', $anime_id)->first();
                if (is_null($anime->userReview)) {
                    DB::transaction(function () use ($anime, $submit_reviews, $key) {
                        $this->animeRepository->createMyReviewByReviewsRequest($anime, $submit_reviews, $key);
                        $this->updateScoreStatistics($anime);
                    });
                } else {
                    DB::transaction(function () use ($anime, $submit_reviews, $key) {
                        $this->animeRepository->updateMyReviewByReviewsRequest($anime, $submit_reviews, $key);
                        $this->updateScoreStatistics($anime);
                    });
                } 
              }
        }
    }

    /**
     * アニメの得点統計情報を更新
     *
     * @param Anime $anime
     * @return void
     */
    public function updateScoreStatistics(Anime $anime)
    {
        $user_reviews = $this->animeRepository->getUserReviewsOfAnime($anime);
        $anime->median = $user_reviews->median('score');
        $anime->average = $user_reviews->avg('score');
        $anime->max = $user_reviews->max('score');
        $anime->min = $user_reviews->min('score');
        $anime->count = $user_reviews->count();
        $this->animeRepository->update($anime);
    }
}
