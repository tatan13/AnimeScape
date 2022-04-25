<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CastService;
use App\Services\AnimeService;
use App\Services\UserService;

class CastController extends Controller
{
    private $castService;
    private $animeService;
    private $userService;

    public function __construct(
        CastService $castService,
        AnimeService $animeService,
        UserService $userService,
    ) {
        $this->castService = $castService;
        $this->animeService = $animeService;
        $this->userService = $userService;
    }

    /**
     * 声優の情報を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cast = $this->castService->getCast($id);
        $act_animes = $this->animeService->getActAnimes($cast);
        return view('cast', [
            'cast' => $cast,
            'act_animes' => $act_animes,
        ]);
    }

    /**
     * 声優のお気に入り登録処理
     *
     * @param int $id
     * @return void
     */
    public function like($id)
    {
        $cast = $this->castService->getCast($id);
        $this->userService->likeCast($cast);
    }

    /**
     * 声優のお気に入り解除処理
     *
     * @param int $id
     * @return void
     */
    public function unlike($id)
    {
        $cast = $this->castService->getCast($id);
        $this->userService->unlikeCast($cast);
    }
}
