<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cast;
use App\Repositories\UserRepository;
use App\Http\Requests\ConfigRequest;
use Illuminate\Support\Facades\Auth;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     *
     */
    public function getUserWithLikeUsersAndLikeCasts(String $uid)
    {
        return $this->userRepository->getByUidWithLikeUsersAndLikeCasts($uid);
    }

    /**
     *
     */
    public function getUser(String $uid)
    {
        return $this->userRepository->getByUid($uid);
    }

    /**
     *
     */
    public function getAuthUser()
    {
        return $this->userRepository->getAuthUser();
    }

    /**
     *
     */
    public function getLikeUserList(User $user)
    {
        return $this->userRepository->getLikeUserList($user);
    }

    /**
     *
     */
    public function getLikedUserList(User $user)
    {
        return $this->userRepository->getLikedUserList($user);
    }

    public function likeUser(User $user)
    {
        // お気に入り登録済みでなく，自分自身でもない場合にお気に入り登録処理
        if (!Auth::user()->isLikeUser($user->id) && $this->isNotMe($user)) {
            $this->userRepository->likeUser($user);
        }
    }

    public function unlikeUser(User $user)
    {
        // お気に入り登録済みで，自分自身でもない場合にお気に入り解除処理
        if (Auth::user()->isLikeUser($user->id) && $this->isNotMe($user)) {
            $this->userRepository->unlikeUser($user);
        }
    }

    public function isNotMe(User $user)
    {
        return Auth::user()->id != $user->id;
    }

    public function likeCast(Cast $cast)
    {
        if (!Auth::user()->isLikeCast($cast->id)) {
            $this->userRepository->likeCast($cast);
        }
    }

    public function unlikeCast(Cast $cast)
    {
        if (Auth::user()->isLikeCast($cast->id)) {
            $this->userRepository->unlikeCast($cast);
        }
    }

    /**
     *
     */
    public function updateConfig(ConfigRequest $request)
    {
        return $this->userRepository->updateConfig($request);
    }
}