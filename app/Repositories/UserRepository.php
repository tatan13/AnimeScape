<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Cast;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;
use Illuminate\Support\Facades\Auth;

class UserRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return User::class;
    }

    public function getByUidWithLikeUsersAndLikeCasts(string $uid)
    {
        return User::where('uid', $uid)->with(['userLikeUsers', 'likeCasts'])->first();
    }

    public function getByUid(string $uid)
    {
        return User::where('uid', $uid)->first();
    }

    /**
     *
     */
    public function getAuthUser()
    {
        return Auth::user();
    }

    /**
     *
     */
    public function getUserReviewsForAll(User $user)
    {
        return $user->userReviews()->with('anime')->get();
    }

    /**
     *
     */
    public function getUserReviewsForEachYear(User $user, Request $request)
    {
        return $user->userReviews()->with('anime')->whereHas('anime', function ($query) use ($request) {
            $query->whereYear($request->year);
        })->get();
    }

    /**
     *
     */
    public function getUserReviewsForEachCoor(User $user, Request $request)
    {
        return $user->userReviews()->with('anime')->whereHas('anime', function ($query) use ($request) {
            $query->whereYear($request->year)->whereCoor($request->year);
        })->get();
    }

    public function getWillWatchAnimeList(User $user)
    {
        return $user->reviewAnimes()->where('user_reviews.will_watch', true)->get();
    }

    /**
     *
     */
    public function getLikeUserList(User $user)
    {
        return $user->userLikeUsers()->with(['userReviews' => function ($query) {
            $query->latest('updated_at');
        }])->get();
    }

    /**
     *
     */
    public function getLikedUserList(User $user)
    {
        return $user->userLikedUsers()->with(['userReviews' => function ($query) {
            $query->latest('updated_at');
        }])->get();
    }


    public function getLikeCastList(User $user)
    {
        return $user->likeCasts;
    }

    public function likeUser(User $user)
    {
        Auth::user()->userLikeUsers()->attach($user->id);
    }

    public function unlikeUser(User $user)
    {
        Auth::user()->userLikeUsers()->detach($user->id);
    }

    public function likeCast(Cast $cast)
    {
        Auth::user()->likeCasts()->attach($cast->id);
    }

    public function unlikeCast(Cast $cast)
    {
        Auth::user()->likeCasts()->detach($cast->id);
    }

    /**
     *
     */
    public function updateConfig(ConfigRequest $request)
    {
        return Auth::user()->update($request->validated());
    }
}