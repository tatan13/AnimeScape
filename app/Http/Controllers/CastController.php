<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CastService;
use App\Services\UserService;

class CastController extends Controller
{
    private CastService $castService;
    private UserService $userService;

    public function __construct(
        CastService $castService,
        UserService $userService,
    ) {
        $this->castService = $castService;
        $this->userService = $userService;
    }

    /**
     * 声優の情報を表示
     *
     * @param int $cast_id
     * @return \Illuminate\View\View
     */
    public function show($cast_id)
    {
        $cast = $this->castService->getCastWithActAnimesWithMyReviews($cast_id);
        return view('cast', [
            'cast' => $cast,
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
}
