<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CreaterService;
use App\Services\UserService;

class CreaterController extends Controller
{
    private CreaterService $createrService;
    private UserService $userService;

    public function __construct(
        CreaterService $createrService,
        UserService $userService,
    ) {
        $this->createrService = $createrService;
        $this->userService = $userService;
    }

    /**
     * クリエイターの情報を表示
     *
     * @param int $creater_id
     * @return \Illuminate\View\View
     */
    public function show($creater_id)
    {
        $creater = $this->createrService->getCreaterWithAnimesWithCompaniesAndWithMyReviews($creater_id);
        return view('creater', [
            'creater' => $creater,
        ]);
    }

    /**
     * クリエイターのお気に入り登録処理
     *
     * @param int $creater_id
     * @return void
     */
    public function like($creater_id)
    {
        $creater = $this->createrService->getCreater($creater_id);
        $this->userService->likeCreater($creater);
    }

    /**
     * クリエイターのお気に入り解除処理
     *
     * @param int $creater_id
     * @return void
     */
    public function unlike($creater_id)
    {
        $creater = $this->createrService->getCreater($creater_id);
        $this->userService->unlikeCreater($creater);
    }

    /**
     * クリエイターを取得し、REST API形式で出力
     *
     * @param int $creater_id
     * @return string
     */
    public function getCreaterNameById($creater_id)
    {
        $creater_name = $this->createrService->getCreaterNameForApi($creater_id);
        return $creater_name;
    }

    /**
     * クリエイターリストを表示
     *
     * @return \Illuminate\View\View
     */
    public function showList()
    {
        $creater_all = $this->createrService->getCreaterAll();
        return view('creater_list', [
            'creater_all' => $creater_all,
        ]);
    }
}
