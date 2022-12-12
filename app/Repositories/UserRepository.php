<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserReview;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Creater;
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
     * idによってユーザーをユーザー統計情報と共にリクエストに従って取得
     *
     * @param int $user_id
     * @param Request $request
     * @return User
     */
    public function getByIdWithUserReviewsAndAnimeFor($user_id, Request $request)
    {
        return User::whereId($user_id)->with('userReviews', function ($query) use ($request) {
            $query->whereHas('anime', function ($query) use ($request) {
                $query->whereYear($request->year)->whereCoor($request->coor);
            })->with('anime');
        })->firstOrFail();
    }

    /**
     * アニメを視聴済みのログインユーザーのお気に入りユーザーを取得
     *
     * @param Anime $anime
     * @return Collection<int,User> | Collection<null>
     */
    public function getWatchAnimeLikeUsersOfLoginUser(Anime $anime)
    {
        return Auth::user()->userLikeUsers()->whereHas('userReview', function ($query) use ($anime) {
            $query->where('watch', 1)->where('anime_id', $anime->id);
        })->with('userReview', function ($query) use ($anime) {
            $query->where('watch', 1)->where('anime_id', $anime->id);
        })->get();
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
     * ユーザーのお気に入りクリエイターリストを取得
     *
     * @param User $user
     * @return Collection<int,Creater> | Collection<null>
     */
    public function getLikeCreaterList(User $user)
    {
        return $user->likeCreaters;
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
     * クリエイターをお気に入り登録
     *
     * @param Creater $creater
     * @return void
     */
    public function likeCreater(Creater $creater)
    {
        Auth::user()->likeCreaters()->attach($creater->id);
    }

    /**
     * クリエイターをお気に入り解除
     *
     * @param Creater $creater
     * @return void
     */
    public function unlikeCreater(Creater $creater)
    {
        Auth::user()->likeCreaters()->detach($creater->id);
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

    /**
     * ユーザーのレビューが存在するか判定
     *
     * @param int $user_id
     * @return bool
     */
    public function isReviewUser($user_id)
    {
        return User::findOrFail($user_id)->userReviews()->exists();
    }
}
