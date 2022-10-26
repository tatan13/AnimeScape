<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cast;
use App\Models\Creater;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    private UserRepository $userRepository;

    /**
     * コンストラクタ
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * ユーザーをユーザー統計情報と共に取得
     *
     * @param int $user_id
     * @param Request $request
     * @return User
     */
    public function getUserWithInformation($user_id, Request $request)
    {
        $user_information = null;
        if (is_null($request->year) && is_null($request->coor)) {
            $user_information = $this->userRepository->getByIdWithUserReviewsAndAnimeForAll($user_id);
        }
        if (!is_null($request->year) && is_null($request->coor)) {
            $user_information = $this->userRepository
            ->getByIdWithUserReviewsAndAnimeForEachYear($user_id, $request);
        }
        if (!is_null($request->year) && !is_null($request->coor)) {
            $user_information = $this->userRepository
            ->getByIdWithUserReviewsAndAnimeForEachCoor($user_id, $request);
        }
        if (is_null($request->year) && !is_null($request->coor)) {
            abort(404);
        }
        $user_information['score_count'] = $user_information->userReviews->whereNotNull('score')->count();
        $user_information['score_average'] = (int)$user_information->userReviews->avg('score');
        $user_information['score_median'] = $user_information->userReviews->median('score');
        $user_information['comments_count'] = $user_information->userReviews->filter(function ($value, $key) {
            return !is_null($value['one_word_comment']) || !is_null($value['long_word_comment']);
        })->count();
        $user_information['will_watches_count'] = $user_information->userReviews->where('will_watch', true)->count();
        $user_information['give_ups_count'] = $user_information->userReviews->where('give_up', true)->count();
        $user_information['now_watches_count'] = $user_information->userReviews->where('now_watch', true)->count();
        $user_information['watches_count'] = $user_information->userReviews->where('watch', true)->count();
        $user_information['before_score_count'] = $user_information->userReviews->whereNotNull('before_score')
        ->count();
        $user_information['before_comments_count'] = $user_information->userReviews->whereNotNull('before_comment')
        ->count();
        for ($i = 100; $i >= 0; $i = $i - 5) {
            $user_information["score_{$i}_anime_reviews"] = $user_information->userReviews
            ->whereBetWeen('score', [$i, $i + 4])->whereNotNull('score')->sortByDesc('score');
        }
        return $user_information;
    }

    /**
     * ユーザーをuser_idから取得
     *
     * @param int $user_id
     * @return User
     */
    public function getUserById($user_id)
    {
        return $this->userRepository->getById($user_id);
    }

    /**
     * ログインユーザーを取得
     *
     * @return User
     */
    public function getAuthUser()
    {
        return $this->userRepository->getAuthUser();
    }

    /**
     * ユーザーのお気に入りユーザーリストを最新のユーザーレビューと共に取得
     *
     * @param User $user
     * @return Collection<int,User> | Collection<null>
     */
    public function getLikeUserListWithLatestUserReview(User $user)
    {
        return $this->userRepository->getLikeUserListWithLatestUserReview($user);
    }

    /**
     * ユーザーの被お気に入りユーザーリストを最新のユーザーレビューと共に取得
     *
     * @param User $user
     * @return Collection<int,User> | Collection<null>
     */
    public function getLikedUserListWithLatestUserReview(User $user)
    {
        return $this->userRepository->getLikedUserListWithLatestUserReview($user);
    }

    /**
     * ユーザーをお気に入り登録
     *
     * @param User $user
     * @return void
     */
    public function likeUser(User $user)
    {
        // お気に入り登録済みでなく，自分自身でもない場合にお気に入り登録処理
        if (!Auth::user()->isLikeUser($user->id) && $this->isNotMe($user)) {
            $this->userRepository->likeUser($user);
        }
    }

    /**
     * ユーザーのお気に入り解除
     *
     * @param User $user
     * @return void
     */
    public function unlikeUser(User $user)
    {
        // お気に入り登録済みで，自分自身でもない場合にお気に入り解除処理
        if (Auth::user()->isLikeUser($user->id) && $this->isNotMe($user)) {
            $this->userRepository->unlikeUser($user);
        }
    }

    /**
     * 引数のユーザーが自分でないか判定
     *
     * @param User $user
     * @return bool
     */
    public function isNotMe(User $user)
    {
        return Auth::user()->id != $user->id;
    }

    /**
     * 声優をお気に入り登録
     *
     * @param Cast $cast
     * @return void
     */
    public function likeCast(Cast $cast)
    {
        if (!Auth::user()->isLikeCast($cast->id)) {
            $this->userRepository->likeCast($cast);
        }
    }

    /**
     * 声優をお気に入り解除
     *
     * @param Cast $cast
     * @return void
     */
    public function unlikeCast(Cast $cast)
    {
        if (Auth::user()->isLikeCast($cast->id)) {
            $this->userRepository->unlikeCast($cast);
        }
    }

    /**
     * クリエイターをお気に入り登録
     *
     * @param Creater $creater
     * @return void
     */
    public function likeCreater(Creater $creater)
    {
        if (!Auth::user()->isLikeCreater($creater->id)) {
            $this->userRepository->likeCreater($creater);
        }
    }

    /**
     * クリエイターをお気に入り解除
     *
     * @param Creater $creater
     * @return void
     */
    public function unlikeCreater(Creater $creater)
    {
        if (Auth::user()->isLikeCreater($creater->id)) {
            $this->userRepository->unlikeCreater($creater);
        }
    }

    /**
     * ユーザーの個人情報を更新
     *
     * @param ConfigRequest $request
     * @return void
     */
    public function updateConfig(ConfigRequest $request)
    {
        $this->userRepository->updateConfig($request);
    }
}
