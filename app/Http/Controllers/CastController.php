<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CastService;
use App\Services\UserService;
use App\Services\UserReviewService;

class CastController extends Controller
{
    private CastService $castService;
    private UserService $userService;
    private UserReviewService $userReviewService;

    public function __construct(
        CastService $castService,
        UserService $userService,
        UserReviewService $userReviewService,
    ) {
        $this->castService = $castService;
        $this->userService = $userService;
        $this->userReviewService = $userReviewService;
    }

    /**
     * 声優の情報を表示
     *
     * @param int $cast_id
     * @return \Illuminate\View\View
     */
    public function show($cast_id)
    {
        $cast = $this->castService->getCastInformationById($cast_id);
        $user_reviews = $this->userReviewService->getCastUserScoreReview($cast);
        $liked_users = $this->userService->getLikedUsersOfCastForRanking($cast);
        return view('cast', [
            'cast' => $cast,
            'user_reviews' => $user_reviews,
            'liked_users' => $liked_users,
        ]);
    }

    /**
     * 声優のお気に入り登録処理
     *
     * @param int $cast_id
     * @return void
     */
    public function like($cast_id)
    {
        $cast = $this->castService->getCast($cast_id);
        $this->userService->likeCast($cast);
    }

    /**
     * 声優のお気に入り解除処理
     *
     * @param int $cast_id
     * @return void
     */
    public function unlike($cast_id)
    {
        $cast = $this->castService->getCast($cast_id);
        $this->userService->unlikeCast($cast);
    }

    /**
     * 声優を取得し、REST API形式で出力
     *
     * @param int $cast_id
     * @return string
     */
    public function getCastNameById($cast_id)
    {
        $cast_name = $this->castService->getCastNameForApi($cast_id);
        return $cast_name;
    }

    /**
     * 声優リストを表示
     *
     * @return \Illuminate\View\View
     */
    public function showList()
    {
        $cast_all = $this->castService->getCastAll();
        return view('cast_list', [
            'cast_all' => $cast_all,
        ]);
    }
}
