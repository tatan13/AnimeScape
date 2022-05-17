<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModifyAnimeRequest;
use App\Http\Requests\ModifyCastRequest;
use App\Http\Requests\DeleteAnimeRequest;
use App\Services\ModifyService;
use App\Services\AnimeService;
use App\Services\CastService;
use Illuminate\Http\Request;

class ModifyController extends Controller
{
    private ModifyService $modifyService;
    private AnimeService $animeService;
    private CastService $castService;

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
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showModifyAnimeRequest($anime_id)
    {
        $anime = $this->animeService->getAnime($anime_id);
        return view('modify_anime_request', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの基本情報修正申請をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $anime_id
     * @param ModifyAnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyAnimeRequest($anime_id, ModifyAnimeRequest $request)
    {
        $this->modifyService->createModifyAnimeRequest($anime_id, $request);
        return redirect()->route('modify_anime_request.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの基本情報修正を処理し，元の画面にリダイレクト
     *
     * @param int $modify_anime_id
     * @param ModifyAnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveModifyAnimeRequest($modify_anime_id, ModifyAnimeRequest $request)
    {
        $this->modifyService->updateAnimeInformationByRequest($modify_anime_id, $request);
        return redirect()->route('modify_request_list.show')->with('flash_modify_anime_request_message', '変更が完了しました。');
    }

    /**
     * アニメの基本情報修正申請を却下し，元の画面にリダイレクト
     *
     * @param int $modify_anime_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectModifyAnimeRequest($modify_anime_id)
    {
        $this->modifyService->rejectModifyAnimeRequest($modify_anime_id);
        return redirect()->route('modify_request_list.show')->with('flash_modify_anime_request_message', '削除が完了しました。');
    }

    /**
     * アニメ，声優の情報修正申請リストを表示
     * @return \Illuminate\View\View
     */
    public function showModifyRequestList()
    {
        $modify_anime_request_list = $this->modifyService->getModifyAnimeRequestListWithAnime();
        $anime_list = $this->animeService->getAnimeListWithModifyOccupationRequestList();
        $modify_cast_request_list = $this->modifyService->getModifyCastRequestListWithCast();
        $delete_anime_request_list = $this->modifyService->getDeleteAnimeRequestWithAnime();
        return view('modify_request_list', [
            'modify_anime_request_list' => $modify_anime_request_list,
            'anime_list' => $anime_list,
            'modify_cast_request_list' => $modify_cast_request_list,
            'delete_anime_request_list' => $delete_anime_request_list,
        ]);
    }

    /**
     * アニメの出演声優情報修正申請ページを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showModifyOccupationsRequest($anime_id)
    {
        $anime = $this->animeService->getAnimeWithActCastsById($anime_id);
        return view('modify_occupations_request', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの出演声優情報修正申請をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $anime_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyOccupationsRequest($anime_id, Request $request)
    {
        $this->modifyService->createModifyOccupationsRequest($anime_id, $request);
        return redirect()->route('modify_occupations_request.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの出演声優情報修正申請を処理し，元の画面にリダイレクト
     *
     * @param int $anime_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveModifyOccupationsRequest($anime_id, Request $request)
    {
        $this->modifyService->updateAnimeCastsByRequest($anime_id, $request);
        return redirect()->route('modify_request_list.show')->with(
            'flash_modify_occupations_request_message',
            '変更が完了しました。'
        );
    }

    /**
     * アニメの出演声優情報修正申請を却下し，元の画面にリダイレクト
     *
     * @param int $anime_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectModifyOccupationsRequest($anime_id)
    {
        $this->modifyService->rejectModifyOccupationsRequestOfAnimeId($anime_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_modify_occupations_request_message',
            '削除が完了しました。'
        );
    }

    /**
     * 声優情報修正申請ページを表示
     *
     * @param int $cast_id
     * @return \Illuminate\View\View
     */
    public function showModifyCastRequest($cast_id)
    {
        $cast = $this->castService->getCast($cast_id);
        return view('modify_cast_request', [
            'cast' => $cast,
        ]);
    }

    /**
     * 声優情報修正申請をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $cast_id
     * @param ModifyCastRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyCastRequest($cast_id, ModifyCastRequest $request)
    {
        $this->modifyService->createModifyCastRequest($cast_id, $request);
        return redirect()->route('modify_cast_request.show', [
            'cast_id' => $cast_id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * 演声優情報修正申請を処理し，元の画面にリダイレクト
     *
     * @param int $modify_cast_id
     * @param ModifyCastRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveModifyCastRequest($modify_cast_id, ModifyCastRequest $request)
    {
        $this->modifyService->updateCastInformationByRequest($modify_cast_id, $request);
        return redirect()->route('modify_request_list.show')->with(
            'flash_modify_cast_request_message',
            '変更が完了しました。'
        );
    }

    /**
     * 声優情報修正申請を却下し，元の画面にリダイレクト
     *
     * @param int $modify_cast_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectModifyCastRequest($modify_cast_id)
    {
        $this->modifyService->rejectModifyCastRequest($modify_cast_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_modify_cast_request_message',
            '削除が完了しました。'
        );
    }

    /**
     * アニメの削除申請ページを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showDeleteAnimeRequest($anime_id)
    {
        $anime = $this->animeService->getAnime($anime_id);
        return view('delete_anime_request', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの削除申請を作成
     *
     * @param int $anime_id
     * @param DeleteAnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteAnimeRequest($anime_id, DeleteAnimeRequest $request)
    {
        $this->modifyService->createDeleteAnimeRequest($anime_id, $request);
        return redirect()->route('anime.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '削除申請が完了しました。');
    }

    /**
     * アニメの削除申請を処理し，元の画面にリダイレクト
     *
     * @param int $delete_anime_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveDeleteAnimeRequest($delete_anime_id)
    {
        $this->modifyService->deleteAnimeByRequest($delete_anime_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_delete_anime_request_message',
            '削除申請の削除が完了しました。'
        );
    }

    /**
     * アニメの削除申請を却下
     *
     * @param int $delete_anime_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectDeleteAnimeRequest($delete_anime_id)
    {
        $this->modifyService->rejectDeleteAnimeRequest($delete_anime_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_delete_anime_request_message',
            '削除申請の削除が完了しました。'
        );
    }

    /**
     * アニメを削除
     *
     * @param int $anime_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAnime($anime_id)
    {
        $this->modifyService->deleteAnime($anime_id);
        return redirect()->route('index.show');
    }
}
