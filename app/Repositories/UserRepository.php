<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserReview;
use App\Models\Anime;
use App\Models\Cast;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return User::class;
    }

    /**
     * ログインユーザーを取得
     *
     * @return User
     */
    public function getAuthUser()
    {
        return Auth::user();
    }

    /**
     * ユーザーのお気に入りユーザーリストを取得
     *
     * @param User $user
     * @return Collection<int,User> | Collection<null>
     */
    public function getLikeUserList($user)
    {
        return $user->userLikeUsers;
    }

    /**
     * idによってユーザーを全期間のユーザー統計情報と共に取得
     *
     * @param int $user_id
     * @return User
     */
    public function getByIdWithUserReviewsAndAnimeForAll($user_id)
    {
        return User::whereId($user_id)->with('userReviews.anime')->firstOrFail();
    }

    /**
     * nameによってユーザーを年別のユーザー統計情報と共に取得
     *
     * @param int $user_id
     * @param Request $request
     * @return User
     */
    public function getByIdWithUserReviewsAndAnimeForEachYear($user_id, Request $request)
    {
        return User::whereId($user_id)->with('userReviews', function ($query) use ($request) {
            $query->whereHas('anime', function ($query) use ($request) {
                $query->whereYear($request->year);
            })->with('anime');
        })->firstOrFail();
    }

    /**
     * idによってユーザーをクール別のユーザー統計情報と共に取得
     *
     * @param int $user_id
     * @param Request $request
     * @return User
     */
    public function getByIdWithUserReviewsAndAnimeForEachCoor($user_id, Request $request)
    {
        return User::whereId($user_id)->with('userReviews', function ($query) use ($request) {
            $query->whereHas('anime', function ($query) use ($request) {
                $query->whereYear($request->year)->whereCoor($request->coor);
            })->with('anime');
        })->firstOrFail();
    }

    /**
     * ユーザーに紐づく得点の付いているユーザーレビューをアニメと共に降順に取得
     *
     * @param User $user
     * @return Collection<int,UserReview> | Collection<null>
     */
    public function getLatestScoreReviewListWithAnimeWithCompaniesOf(User $user)
    {
        return $user->userReviews()->whereNotNull('score')->with('anime.companies')->latest()->get();
    }

    /**
     * ユーザーの視聴予定アニメリストを取得
     *
     * @param User $user
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getWillWatchAnimeListWithCompanies(User $user)
    {
        return $user->reviewAnimes()->where('user_reviews.will_watch', true)->withCompanies()->get();
    }

    /**
     * ユーザーのお気に入りユーザーリストを最新のユーザーレビューと共に取得
     *
     * @param User $user
     * @return Collection<int,User> | Collection<null>
     */
    public function getLikeUserListWithLatestUserReview(User $user)
    {
        return $user->userLikeUsers()->with('latestUserReviewUpdatedAt')->get();
    }

    /**
     * ユーザーの被お気に入りユーザーリストを最新のユーザーレビューと共に取得
     *
     * @param User $user
     * @return Collection<int,User> | Collection<null>
     */
    public function getLikedUserListWithLatestUserReview(User $user)
    {
        return $user->userLikedUsers()->with('latestUserReviewUpdatedAt')->get();
    }

    /**
     * ユーザーのお気に入り声優リストを取得
     *
     * @param User $user
     * @return Collection<int,Cast> | Collection<null>
     */
    public function getLikeCastList(User $user)
    {
        return $user->likeCasts;
    }

    /**
     * ユーザーをお気に入り登録
     *
     * @param User $user
     * @return void
     */
    public function likeUser(User $user)
    {
        Auth::user()->userLikeUsers()->attach($user->id);
    }

    /**
     * ユーザーのお気に入り解除
     *
     * @param User $user
     * @return void
     */
    public function unlikeUser(User $user)
    {
        Auth::user()->userLikeUsers()->detach($user->id);
    }

    /**
     * 声優をお気に入り登録
     *
     * @param Cast $cast
     * @return void
     */
    public function likeCast(Cast $cast)
    {
        Auth::user()->likeCasts()->attach($cast->id);
    }

    /**
     * 声優をお気に入り解除
     *
     * @param Cast $cast
     * @return void
     */
    public function unlikeCast(Cast $cast)
    {
        Auth::user()->likeCasts()->detach($cast->id);
    }

    /**
     * ユーザーの個人情報を更新
     *
     * @param ConfigRequest $request
     * @return void
     */
    public function updateConfig(ConfigRequest $request)
    {
        Auth::user()->update($request->validated());
    }
}
