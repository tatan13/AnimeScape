<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\UserReview;
use App\Models\User;
use App\Repositories\UserReviewRepository;
use App\Repositories\UserRepository;
use App\Repositories\AnimeRepository;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserReviewService
{
    private $userReviewRepository;
    private $animeRepository;

    public function __construct(
        UserReviewRepository $userReviewRepository,
        UserRepository $userRepository,
        AnimeRepository $animeRepository
    )
    {
        $this->userReviewRepository = $userReviewRepository;
        $this->userRepository = $userRepository;
        $this->animeRepository = $animeRepository;
    }

    /**
     *
     */
    public function getUserReview($id)
    {
        return $this->userReviewRepository->getById($id);
    }

    /**
     *
     */
    public function getUserReviewsOfAnime(Anime $anime)
    {
        return $this->animeRepository->getUserReviewsOfAnime($anime);
    }

    public function getLatestUserReviewsOfAnime(Anime $anime)
    {
        return $this->animeRepository->getLatestUserReviews($anime);
    }

    /**
     *
     */
    public function getUserReviewsOfUserFor(User $user, Request $request)
    {
        if (is_null($request->year)) {
            return $this->userRepository->getUserReviewsForAll($user);
        }
        if (!is_null($request->year) && is_null($request->coor)) {
            return $this->userRepository->getUserReviewsForEachYear($user, $request);
        }
        if (!is_null($request->year) && !is_null($request->coor)) {

            return $this->userRepository->getUserReviewsForEachCoor($user, $request);
        }
    }

    /**
     * ログインユーザーのアニメレビューを取得
     * @param Anime $anime
     * @return UserReview
     */
    public function getMyReview(Anime $anime)
    {
        if (Auth::check()){
            $my_review = $this->animeRepository->getMyReview($anime) ?? new UserReview();
        } else {
            $my_review = null;
        }
        return $my_review;
    }

    public function createOrUpdateMyReview(Anime $anime, ReviewRequest $submit_score)
    {
        $my_review = $this->animeRepository->getMyReview($anime);
        if (is_null($my_review)) {
            DB::transaction(function () use ($anime, $submit_score) {
                $this->animeRepository->createMyReview($anime, $submit_score);
                $this->updateScoreStatistics($anime);
            });
        } else {
            DB::transaction(function () use ($anime, $submit_score) {
                $this->animeRepository->updateMyReview($anime, $submit_score);
                $this->updateScoreStatistics($anime);
            });
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