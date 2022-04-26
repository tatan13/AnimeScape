<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModifyAnimeRequest;
use App\Services\ModifyService;
use App\Services\AnimeService;
use App\Services\CastService;
use Illuminate\Http\Request;

class ModifyController extends Controller
{
    private $modifyService;
    private $animeService;
    private $castService;

    public function __construct(
        ModifyService $modifyService,
        AnimeService $animeService,
        CastService $castService,
    ) {
        $this->modifyService = $modifyService;
        $this->animeService = $animeService;
        $this->castService = $castService;
    }

    /**
     * アニメの基本情報修正申請ページを表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showModifyAnime($id)
    {
        $anime = $this->animeService->getAnime($id);
        return view('modify_anime', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの基本情報修正申請をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $id
     * @param ModifyAnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyAnime(ModifyAnimeRequest $request, $id)
    {
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->createModifyAnime($anime, $request);
        return redirect()->route('modify_anime.show', [
            'id' => $id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの基本情報修正を処理し，元の画面にリダイレクト
     *
     * @param int $id
     * @param ModifyAnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateModifyAnime(int $id, ModifyAnimeRequest $request)
    {
        $modify_anime = $this->modifyService->getModifyAnime($id);
        $this->modifyService->updateAnimeInfoBy($modify_anime, $request);
        return redirect()->route('modify_list.show')->with('flash_anime_message', '変更が完了しました。');
    }

    /**
     * アニメの基本情報修正申請を却下し，元の画面にリダイレクト
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteModifyAnime($id)
    {
        $modify_anime = $this->modifyService->getModifyAnime($id);
        $this->modifyService->deleteModifyAnime($modify_anime);
        return redirect()->route('modify_list.show')->with('flash_anime_message', '削除が完了しました。');
    }

    /**
     * アニメ，声優の情報修正申請リストを表示
     * @return \Illuminate\View\View
     */
    public function showModifyList()
    {
        $modify_anime_list = $this->modifyService->getModifyAnimeListWithAnime();
        $anime_list = $this->animeService->getAnimeListWithModifyOccupationList();
        return view('modify_list', [
            'modify_anime_list' => $modify_anime_list,
            'anime_list' => $anime_list,
        ]);
    }

    /**
     * アニメの出演声優情報修正申請ページを表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showModifyOccupation($id)
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
    public function postModifyOccupation(Request $request, $id)
    {
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->createModifyOccupations($anime, $request);
        return redirect()->route('modify_occupation.show', [
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
    public function updateModifyOccupation($id, Request $request)
    {
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->updateAnimeCastsInfo($anime, $request);
        return redirect()->route('modify_list.show')->with('flash_occupation_message', '変更が完了しました。');
    }

    /**
     * アニメの出演声優情報修正依頼を却下し，元の画面にリダイレクト
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteModifyOccupation($id)
    {
        $anime = $this->animeService->getAnime($id);
        $this->modifyService->deleteModifyOccupationsOfAnime($anime);
        return redirect()->route('modify_list.show')->with('flash_occupation_message', '削除が完了しました。');
    }
}
