<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimeRequest;
use App\Http\Requests\CastRequest;
use App\Http\Requests\DeleteRequest;
use App\Services\ModifyService;
use App\Services\AnimeService;
use App\Services\CastService;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class ModifyController extends Controller
{
    private ModifyService $modifyService;
    private AnimeService $animeService;
    private CastService $castService;
    private CompanyService $companyService;

    public function __construct(
        ModifyService $modifyService,
        AnimeService $animeService,
        CastService $castService,
        CompanyService $companyService,
    ) {
        $this->modifyService = $modifyService;
        $this->animeService = $animeService;
        $this->castService = $castService;
        $this->companyService = $companyService;
    }

    /**
     * アニメの基本情報変更申請ページを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showModifyAnimeRequest($anime_id)
    {
        $anime = $this->animeService->getAnimeWithCompanies($anime_id);
        return view('modify_anime_request', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの基本情報変更申請をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $anime_id
     * @param AnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyAnimeRequest($anime_id, AnimeRequest $request)
    {
        $this->modifyService->createModifyAnimeRequest($anime_id, $request);
        return redirect()->route('modify_anime_request.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの基本情報変更を処理し，元の画面にリダイレクト
     *
     * @param int $modify_anime_id
     * @param AnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveModifyAnimeRequest($modify_anime_id, AnimeRequest $request)
    {
        $this->modifyService->updateAnimeInformationByRequest($modify_anime_id, $request);
        return redirect()->route('modify_request_list.show')->with('flash_modify_anime_request_message', '変更が完了しました。');
    }

    /**
     * アニメの基本情報変更申請を却下し，元の画面にリダイレクト
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
     * アニメ，声優の情報変更申請リストを表示
     * @return \Illuminate\View\View
     */
    public function showModifyRequestList()
    {
        $modify_anime_request_list = $this->modifyService->getModifyAnimeRequestListWithAnimeWithCompanies();
        $anime_list = $this->animeService->getAnimeListWithModifyOccupationRequestList();
        $modify_cast_request_list = $this->modifyService->getModifyCastRequestListWithCast();
        $delete_anime_request_list = $this->modifyService->getDeleteAnimeRequestListWithAnime();
        $add_anime_request_list = $this->modifyService->getAddAnimeRequestListDeleteUnFlag();
        $add_cast_request_list = $this->modifyService->getAddCastRequestListDeleteUnFlag();
        $delete_cast_request_list = $this->modifyService->getDeleteCastRequestListWithCast();
        $delete_company_request_list = $this->modifyService->getDeleteCompanyRequestListWithCompany();
        return view('modify_request_list', [
            'modify_anime_request_list' => $modify_anime_request_list,
            'anime_list' => $anime_list,
            'modify_cast_request_list' => $modify_cast_request_list,
            'delete_anime_request_list' => $delete_anime_request_list,
            'add_anime_request_list' => $add_anime_request_list,
            'add_cast_request_list' => $add_cast_request_list,
            'delete_cast_request_list' => $delete_cast_request_list,
            'delete_company_request_list' => $delete_company_request_list,
        ]);
    }

    /**
     * アニメの出演声優情報変更ページを表示
     *
     * @param int $anime_id
     * @return \Illuminate\View\View
     */
    public function showModifyOccupations($anime_id)
    {
        $anime = $this->animeService->getAnimeWithActCastsWithOccupationsById($anime_id);
        return view('modify_occupations', [
            'anime' => $anime,
        ]);
    }

    /**
     * アニメの出演声優情報を変更し，元の画面にリダイレクト
     *
     * @param int $anime_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyOccupations($anime_id, Request $request)
    {
        $this->modifyService->createOrDeleteOrModifyOccupations($anime_id, $request);
        return redirect()->route('modify_occupations.show', [
            'anime_id' => $anime_id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * アニメの出演声優情報変更申請を処理し，元の画面にリダイレクト
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
     * アニメの出演声優情報変更申請を却下し，元の画面にリダイレクト
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
     * 声優情報変更申請ページを表示
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
     * 声優情報変更申請をデータベースに保存し，元の画面にリダイレクト
     *
     * @param int $cast_id
     * @param CastRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postModifyCastRequest($cast_id, CastRequest $request)
    {
        $this->modifyService->createModifyCastRequest($cast_id, $request);
        return redirect()->route('modify_cast_request.show', [
            'cast_id' => $cast_id,
        ])->with('flash_message', '変更申請が完了しました。');
    }

    /**
     * 演声優情報変更申請を処理し，元の画面にリダイレクト
     *
     * @param int $modify_cast_id
     * @param CastRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveModifyCastRequest($modify_cast_id, CastRequest $request)
    {
        $this->modifyService->updateCastInformationByRequest($modify_cast_id, $request);
        return redirect()->route('modify_request_list.show')->with(
            'flash_modify_cast_request_message',
            '変更が完了しました。'
        );
    }

    /**
     * 声優情報変更申請を却下し，元の画面にリダイレクト
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
     * @param DeleteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteAnimeRequest($anime_id, DeleteRequest $request)
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
            'アニメの削除が完了しました。'
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

    /**
     * アニメの追加申請ページを表示
     *
     * @return \Illuminate\View\View
     */
    public function showAddAnimeRequest()
    {
        return view('add_anime_request');
    }

    /**
     * アニメの追加申請を作成
     *
     * @param AnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddAnimeRequest(AnimeRequest $request)
    {
        $this->modifyService->createAddAnimeRequest($request);
        return redirect()->route('add_anime_request.show')->with('flash_message', '追加申請が完了しました。');
    }

    /**
     * アニメの追加申請を処理し，元の画面にリダイレクト
     *
     * @param int $add_anime_id
     * @param AnimeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveAddAnimeRequest($add_anime_id, AnimeRequest $request)
    {
        $this->modifyService->createAnimeByRequest($add_anime_id, $request);
        return redirect()->route('modify_request_list.show')->with(
            'flash_add_anime_request_message',
            'アニメの追加が完了しました。'
        );
    }

    /**
     * アニメの追加申請を却下
     *
     * @param int $add_anime_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectAddAnimeRequest($add_anime_id)
    {
        $this->modifyService->rejectAddAnimeRequest($add_anime_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_add_anime_request_message',
            '追加申請の削除が完了しました。'
        );
    }

    /**
     * 声優の追加申請ページを表示
     *
     * @return \Illuminate\View\View
     */
    public function showAddCastRequest()
    {
        return view('add_cast_request');
    }

    /**
     * 声優の追加申請を作成
     *
     * @param CastRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddCastRequest(CastRequest $request)
    {
        $this->modifyService->createAddCastRequest($request);
        return redirect()->route('add_cast_request.show')->with('flash_message', '追加申請が完了しました。');
    }

    /**
     * 声優の追加申請を処理し，元の画面にリダイレクト
     *
     * @param int $add_cast_id
     * @param CastRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveAddCastRequest($add_cast_id, CastRequest $request)
    {
        $this->modifyService->createCastByRequest($add_cast_id, $request);
        return redirect()->route('modify_request_list.show')->with(
            'flash_add_cast_request_message',
            '声優の追加が完了しました。'
        );
    }

    /**
     * 声優の追加申請を却下
     *
     * @param int $add_cast_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectAddCastRequest($add_cast_id)
    {
        $this->modifyService->rejectAddCastRequest($add_cast_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_add_cast_request_message',
            '追加申請の削除が完了しました。'
        );
    }

    /**
     * 声優の削除申請ページを表示
     *
     * @param int $cast_id
     * @return \Illuminate\View\View
     */
    public function showDeleteCastRequest($cast_id)
    {
        $cast = $this->castService->getCast($cast_id);
        return view('delete_cast_request', [
            'cast' => $cast,
        ]);
    }

    /**
     * 声優の削除申請を作成
     *
     * @param int $cast_id
     * @param DeleteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCastRequest($cast_id, DeleteRequest $request)
    {
        $this->modifyService->createDeleteCastRequest($cast_id, $request);
        return redirect()->route('cast.show', [
            'cast_id' => $cast_id,
        ])->with('flash_message', '削除申請が完了しました。');
    }

    /**
     * 声優の削除申請を処理し，元の画面にリダイレクト
     *
     * @param int $delete_cast_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveDeleteCastRequest($delete_cast_id)
    {
        $this->modifyService->deleteCastByRequest($delete_cast_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_delete_cast_request_message',
            '声優の削除が完了しました。'
        );
    }

    /**
     * 声優の削除申請を却下
     *
     * @param int $delete_cast_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectDeleteCastRequest($delete_cast_id)
    {
        $this->modifyService->rejectDeleteCastRequest($delete_cast_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_delete_cast_request_message',
            '削除申請の削除が完了しました。'
        );
    }

    /**
     * 会社の削除申請ページを表示
     *
     * @param int $company_id
     * @return \Illuminate\View\View
     */
    public function showDeleteCompanyRequest($company_id)
    {
        $company = $this->companyService->getCompany($company_id);
        return view('delete_company_request', [
            'company' => $company,
        ]);
    }

    /**
     * 会社の削除申請を作成
     *
     * @param int $company_id
     * @param DeleteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCompanyRequest($company_id, DeleteRequest $request)
    {
        $this->modifyService->createDeleteCompanyRequest($company_id, $request);
        return redirect()->route('company.show', [
            'company_id' => $company_id,
        ])->with('flash_message', '削除申請が完了しました。');
    }

    /**
     * 会社の削除申請を処理し，元の画面にリダイレクト
     *
     * @param int $delete_company_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveDeleteCompanyRequest($delete_company_id)
    {
        $this->modifyService->deleteCompanyByRequest($delete_company_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_delete_company_request_message',
            '会社の削除が完了しました。'
        );
    }

    /**
     * 会社の削除申請を却下
     *
     * @param int $delete_company_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectDeleteCompanyRequest($delete_company_id)
    {
        $this->modifyService->rejectDeleteCompanyRequest($delete_company_id);
        return redirect()->route('modify_request_list.show')->with(
            'flash_delete_company_request_message',
            '削除申請の削除が完了しました。'
        );
    }

    /**
     * 作品の追加履歴を表示
     *
     * @return \Illuminate\View\View
     */
    public function showAddAnimeLog()
    {
        $add_anime_list = $this->modifyService->getAddAnimeListLatest();
        return view('add_anime_log', [
            'add_anime_list' => $add_anime_list,
        ]);
    }

    /**
     * 声優の追加履歴を表示
     *
     * @return \Illuminate\View\View
     */
    public function showAddCastLog()
    {
        $add_cast_list = $this->modifyService->getAddCastListLatest();
        return view('add_cast_log', [
            'add_cast_list' => $add_cast_list,
        ]);
    }
}
