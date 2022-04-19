<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModifyAnimeRequest;
use App\Services\ModifyService;
use App\Services\AnimeService;
use App\Services\CastService;
use App\Services\ExceptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModifyController extends Controller
{
    private $modifyService;
    private $animeService;
    private $castService;
    private $exceptionService;

    public function __construct(
        ModifyService $modifyService,
        AnimeService $animeService,
        CastService $castService,
        ExceptionService $exceptionService
    )
    {
        $this->modifyService = $modifyService;
        $this->animeService = $animeService;
        $this->castService = $castService;
        $this->exceptionService = $exceptionService;
    }

    /**
     * アニメの基本情報修正画面を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function modifyAnimeShow($id)
    {
        $anime = $this->animeService->getAnime($id);
        return view('modify_anime', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの基本情報修正依頼をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyAnimePost(ModifyAnimeRequest $request, $id)
    {
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->createModifyAnime($anime, $request);
        return redirect()->route('modify.anime.show', [
            'id' => $id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの基本情報修正を処理し，元の画面にリダイレクト
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyAnimeUpdate(int $id, ModifyAnimeRequest $request)
    {
        $this->exceptionService->render404IfNotRootUser();
        $modify_anime = $this->modifyService->getModifyAnime($id);
        $this->modifyService->updateAnimeInfoBy($modify_anime, $request);
        return redirect()->route('modify.list.show')->with('flash_anime_message', '変更が完了しました。');
    }

    /**
     * アニメの基本情報修正依頼を却下し，元の画面にリダイレクト
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyAnimeDelete($id)
    {
        $this->exceptionService->render404IfNotRootUser();
        $modify_anime = $this->modifyService->getModifyAnime($id);
        $this->modifyService->deleteModifyAnime($modify_anime);
        return redirect()->route('modify.list.show')->with('flash_anime_message', '削除が完了しました。');
    }

    /**
     * アニメ，声優の情報修正依頼リストを表示
     * @return \Illuminate\View\View
     */
    public function modifyListShow()
    {
        $this->exceptionService->render404IfNotRootUser();
        $modify_animes = $this->modifyService->getModifyAnimeList();
        //アニメに出演する声優の情報修正依頼リスト
        $modify_occupations_list = $this->modifyService->getModifyOccupationsList();
        return view('modify_list', [
            'modify_animes' => $modify_animes,
            'modify_occupations_list' => $modify_occupations_list,
        ]);
    }

    /**
     * アニメの出演声優情報修正依頼画面を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function modifyOccupationShow($id)
    {
        $anime = $this->animeService->getAnime($id);
        $act_casts = $this->castService->getActCasts($anime);
        return view('modify_occupation', [
            'anime' => $anime,
            'act_casts' => $act_casts,
        ]);
    }

    /**
     * アニメの出演声優情報修正依頼をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyOccupationPost(Request $request, $id)
    {
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->createModifyOccupations($anime, $request);
        return redirect()->route('modify.occupation.show', [
            'id' => $id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの出演声優情報修正依頼を処理し，元の画面にリダイレクト
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyOccupationUpdate($id, Request $request)
    {
        $this->exceptionService->render404IfNotRootUser();
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->updateAnimeCastsInfo($anime, $request);
        return redirect()->route('modify.list.show')->with('flash_occupation_message', '変更が完了しました。');
    }

    /**
     * アニメの出演声優情報修正依頼を却下し，元の画面にリダイレクト
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modifyOccupationDelete($id)
    {
        $this->exceptionService->render404IfNotRootUser();
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->deleteModifyOccupationsOfAnime($anime);
        return redirect()->route('modify.list.show')->with('flash_occupation_message', '削除が完了しました。');
    }
}
