<?php

namespace App\Services;

use App\Models\UserReview;
use App\Models\User;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Company;
use App\Models\Tag;
use App\Repositories\UserReviewRepository;
use App\Repositories\AnimeRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\AnimeTagReviewRequest;
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
        AnimeRepository $animeRepository,
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
     * 声優に紐づく得点の付いたユーザーレビューを取得
     *
     * @param Cast $cast
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getCastUserScoreReview(Cast $cast)
    {
        return $this->userReviewRepository->getCastUserScoreReview($cast);
    }

    /**
     * 会社に紐づく得点の付いたユーザーレビューを取得
     *
     * @param Company $company
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getCompanyUserScoreReview(Company $company)
    {
        return $this->userReviewRepository->getCompanyUserScoreReview($company);
    }

    /**
     * ユーザーレビューを感想タイムスタンプ降順に並び替えて取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserReviewListLatestCommentWithAnimeAndUser()
    {
        return $this->userReviewRepository->getUserReviewListLatestCommentWithAnimeAndUser();
    }

    /**
     * ユーザーレビューを視聴完了前感想タイムスタンプ降順に並び替えて取得
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserReviewListLatestBeforeCommentWithAnimeAndUser()
    {
        return $this->userReviewRepository->getUserReviewListLatestBeforeCommentWithAnimeAndUser();
    }

    /**
         * ユーザーレビューを感想タイムスタンプ降順に並び替えて7個まで取得
         *
         * @return Collection<int,UserReview> | Collection<null>
         */
    public function getUserReviewListLatestCommentLimitWithAnimeAndUser()
    {
        return $this->userReviewRepository->getUserReviewListLatestCommentLimitWithAnimeAndUser();
    }

    /**
     * ユーザーレビューを視聴完了前感想タイムスタンプ降順に並び替えて7個まで取得
     *
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getUserReviewListLatestBeforeCommentLimitWithAnimeAndUser()
    {
        return $this->userReviewRepository->getUserReviewListLatestBeforeCommentLimitWithAnimeAndUser();
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
            'before_score_timestamp' => (($my_review->before_score ?? null) != $submit_review->before_score)
             ? Carbon::now() : $my_review->before_score_timestamp ?? null,
            'before_comment_timestamp' => ((($my_review->before_comment ?? null) != $submit_review->before_comment) ||
            (($my_review->before_long_comment ?? null) != $submit_review->before_long_comment))
             ? Carbon::now() : $my_review->before_comment_timestamp ?? null,
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
        $anime_list = $this->animeRepository->getAnimeListWithMyReviewsByAnimeIdArray($submit_reviews->anime_id);
        foreach ($submit_reviews->anime_id as $key => $anime_id) {
            $anime = $anime_list->where('id', $anime_id)->first() ?? abort(404);
            // 何かしら入力されていた場合、レビューを作成
            if (
                !is_null($submit_reviews->score[$key] ?? null) ||
                ($submit_reviews->watch[$key] ?? 0) == true ||
                ($submit_reviews->will_watch[$key] ?? 0) != 0 ||
                ($submit_reviews->now_watch[$key] ?? 0) == true ||
                ($submit_reviews->give_up[$key] ?? 0) == true ||
                !is_null($submit_reviews->number_of_interesting_episode[$key] ?? null) ||
                !is_null($submit_reviews->one_word_comment[$key] ?? null) ||
                !is_null($submit_reviews->before_score[$key] ?? null) ||
                !is_null($submit_reviews->before_comment[$key] ?? null) ||
                !is_null($submit_reviews->number_of_watched_episode[$key] ?? null)
            ) {
                if (is_null($anime->userReview)) {
                    DB::transaction(function () use ($anime, $submit_reviews, $key) {
                        if ($submit_reviews->type == 'after') {
                            $this->animeRepository->createMyReviewByReviewsRequest($anime, $submit_reviews, $key);
                        }
                        if ($submit_reviews->type == 'before') {
                            $this->animeRepository->createMyBeforeReviewByReviewsRequest($anime, $submit_reviews, $key);
                        }
                        $this->updateScoreStatistics($anime);
                    });
                    continue;
                }
                DB::transaction(function () use ($anime, $submit_reviews, $key) {
                    if ($submit_reviews->type == 'after') {
                        $this->animeRepository->updateMyReviewByReviewsRequest(
                            $anime,
                            $anime->userReview,
                            $submit_reviews,
                            $key
                        );
                    }
                    if ($submit_reviews->type == 'before') {
                        $this->animeRepository->updateMyBeforeReviewByReviewsRequest(
                            $anime,
                            $anime->userReview,
                            $submit_reviews,
                            $key
                        );
                    }
                    $this->updateScoreStatistics($anime);
                });
                continue;
            }
            // 何も入力されておらず、既にレビューが存在する場合、nullで更新
            if (!is_null($anime->userReview)) {
                DB::transaction(function () use ($anime, $submit_reviews, $key) {
                    if ($submit_reviews->type == 'after') {
                        $this->animeRepository->updateMyReviewByReviewsRequest(
                            $anime,
                            $anime->userReview,
                            $submit_reviews,
                            $key
                        );
                    }
                    if ($submit_reviews->type == 'before') {
                        $this->animeRepository->updateMyBeforeReviewByReviewsRequest(
                            $anime,
                            $anime->userReview,
                            $submit_reviews,
                            $key
                        );
                    }
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
        $anime->before_median = $user_reviews->median('before_score');
        $anime->before_average = $user_reviews->avg('before_score');
        $anime->before_count = $user_reviews->whereNotNull('before_score')->count();
        $anime->before_stdev = self::standardDeviation($user_reviews->whereNotNull('before_score')
        ->pluck('before_score')->toArray());
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
     * ユーザーのアニメのユーザーレビューをアニメ感想表示のために取得
     *
     * @param int $user_review_id
     * @return UserReview
     */
    public function getUserReviewWithAnimeAndUserNotNullOneWordComment($user_review_id)
    {
        return $this->userReviewRepository->getUserReviewWithAnimeAndUserNotNullOneWordComment($user_review_id);
    }

    /**
     * ユーザーのアニメのユーザーレビューをアニメ視聴完了前感想表示のために取得
     *
     * @param int $user_review_id
     * @return UserReview
     */
    public function getUserReviewForAnimeBeforeComment($user_review_id)
    {
        return $this->userReviewRepository->getForAnimeBeforeComment($user_review_id);
    }
}
