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
use Carbon\Carbon;

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
     * user_review_idからユーザーレビューを取得
     *
     * @param int $user_review_id
     * @return UserReview
     */
    public function getUserReview($user_review_id)
    {
        return $this->userReviewRepository->getById($user_review_id);
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
     * @param ReviewRequest $submit_review
     * @return void
     */
    public function createOrUpdateMyReview(Anime $anime, ReviewRequest $submit_review)
    {
        $my_review = $this->animeRepository->getMyReview($anime);
        $update_review = array_merge($submit_review->validated(), [
            'anime_id' => $anime->id,
            'user_id' => Auth::id(),
            'watch_timestamp' => (is_null($my_review ?? null) || ($my_review->watch ?? false) == false) &&
            ($submit_review->watch == true) // watchがfalseからtrueになったときのみ
             ? Carbon::now() : $my_review->watch_timestamp ?? null,
            'comment_timestamp' => (is_null($my_review ?? null) ||
            (is_null($my_review->one_word_comment ?? null) && is_null($my_review->long_word_comment ?? null))) &&
            (!is_null($submit_review->one_word_comment) ||
            !is_null($submit_review->long_word_comment)) // commentがnullからnullではなくなったときのみ
             ? Carbon::now() : $my_review->comment_timestamp ?? null,
        ]);
        DB::transaction(function () use ($anime, $my_review, $update_review) {
            $this->userReviewRepository->createOrUpdateMyReview($my_review->id ?? null, $update_review);
            $this->updateScoreStatistics($anime);
        });
    }

    /**
     * ログインユーザーのアニメに紐づくユーザーレビューを複数作成または更新
     *
     * @param ReviewsRequest $submit_reviews
     * @return void
     */
    public function createOrUpdateMyMultipleReview(ReviewsRequest $submit_reviews)
    {
        $anime_list = $this->animeRepository->getAnimeListWithCompaniesAndWithMyReviewsFor($submit_reviews);
        foreach ($submit_reviews->anime_id as $key => $anime_id) {
            $anime = $anime_list->where('id', $anime_id)->first() ?? abort(404);
            // 何かしら入力されていた場合、レビューを作成
            if (
                !is_null($submit_reviews->score[$key]) ||
                $submit_reviews->watch[$key] == true ||
                $submit_reviews->will_watch[$key] != 0 ||
                $submit_reviews->now_watch[$key] == true ||
                $submit_reviews->give_up[$key] == true ||
                !is_null($submit_reviews->number_of_interesting_episode[$key]) ||
                !is_null($submit_reviews->one_word_comment[$key])
            ) {
                if (is_null($anime->userReview)) {
                    DB::transaction(function () use ($anime, $submit_reviews, $key) {
                        $this->animeRepository->createMyReviewByReviewsRequest($anime, $submit_reviews, $key);
                        $this->updateScoreStatistics($anime);
                    });
                    continue;
                }
                DB::transaction(function () use ($anime, $submit_reviews, $key) {
                    $this->animeRepository->updateMyReviewByReviewsRequest(
                        $anime,
                        $anime->userReview,
                        $submit_reviews,
                        $key
                    );
                    $this->updateScoreStatistics($anime);
                });
                continue;
            }
            // 何も入力されておらず、既にレビューが存在する場合、nullで更新
            if (!is_null($anime->userReview)) {
                DB::transaction(function () use ($anime, $submit_reviews, $key) {
                    $this->animeRepository->updateMyReviewByReviewsRequest(
                        $anime,
                        $anime->userReview,
                        $submit_reviews,
                        $key
                    );
                    $this->updateScoreStatistics($anime);
                });
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
        $anime->number_of_interesting_episode = $user_reviews->median('number_of_interesting_episode');
        $anime->median = $user_reviews->median('score');
        $anime->average = $user_reviews->avg('score');
        $anime->max = $user_reviews->max('score');
        $anime->min = $user_reviews->min('score');
        $anime->count = $user_reviews->whereNotNull('score')->count();
        $anime->stdev = self::standardDeviation($user_reviews->whereNotNull('score')->pluck('score')->toArray());
        $this->animeRepository->update($anime);
    }

    /**
     * 平均を求める
     *
     * @return float | null
     */
    public static function average(array $values)
    {
        $count = count($values);

        if ($count == 0) {
            return null;
        }
        return (float) (array_sum($values) / count($values));
    }

    /**
     * 分散を求める
     *
     * @return float | null
     */
    public static function variance(array $values)
    {
        $count = count($values);

        if ($count == 0) {
            return null;
        }

        // 平均値を求める
        $ave = self::average($values);

        $variance = 0.0;
        foreach ($values as $val) {
            $variance += pow($val - $ave, 2);
        }
        return (float) ($variance / count($values));
    }

    /**
     * 標準偏差を求める
     *
     * @return float | null
     */
    public static function standardDeviation(array $values)
    {
        $variance = self::variance($values);
        if (is_null($variance)) {
            return null;
        }
        return (float) sqrt($variance);
    }

    /**
     * ユーザーのアニメのユーザーレビューをアニメ、ユーザーとともに取得
     *
     * @param int $user_review_id
     * @return UserReview
     */
    public function getUserReviewWithAnimeAndUser($user_review_id)
    {
        return $this->userReviewRepository->getUserReviewWithAnimeAndUser($user_review_id);
    }
}
