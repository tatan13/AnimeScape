<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\ModifyAnime;
use App\Models\AddAnime;
use App\Models\DeleteAnime;
use App\Models\Cast;
use App\Models\ModifyCast;
use App\Models\AddCast;
use App\Models\DeleteCast;
use App\Models\Creater;
use App\Models\ModifyCreater;
use App\Models\AddCreater;
use App\Models\DeleteCreater;
use App\Models\DeleteCompany;
use App\Repositories\AnimeRepository;
use App\Repositories\ModifyAnimeRepository;
use App\Repositories\AddAnimeRepository;
use App\Repositories\DeleteAnimeRepository;
use App\Repositories\CastRepository;
use App\Repositories\ModifyCastRepository;
use App\Repositories\AddCastRepository;
use App\Repositories\DeleteCastRepository;
use App\Repositories\CreaterRepository;
use App\Repositories\ModifyCreaterRepository;
use App\Repositories\AddCreaterRepository;
use App\Repositories\DeleteCreaterRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DeleteCompanyRepository;
use App\Repositories\OccupationRepository;
use App\Repositories\AnimeCreaterRepository;
use Illuminate\Http\Request;
use App\Http\Requests\AnimeRequest;
use App\Http\Requests\CastRequest;
use App\Http\Requests\CreaterRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Mail;
use Gate;

class ModifyService
{
    private AnimeRepository $animeRepository;
    private ModifyAnimeRepository $modifyAnimeRepository;
    private AddAnimeRepository $addAnimeRepository;
    private DeleteAnimeRepository $deleteAnimeRepository;
    private CastRepository $castRepository;
    private ModifyCastRepository $modifyCastRepository;
    private AddCastRepository $addCastRepository;
    private DeleteCastRepository $deleteCastRepository;
    private CreaterRepository $createrRepository;
    private ModifyCreaterRepository $modifyCreaterRepository;
    private AddCreaterRepository $addCreaterRepository;
    private DeleteCreaterRepository $deleteCreaterRepository;
    private CompanyRepository $companyRepository;
    private DeleteCompanyRepository $deleteCompanyRepository;
    private OccupationRepository $occupationRepository;
    private AnimeCreaterRepository $animeCreaterRepository;

    /**
     * コンストラクタ
     *
     * @param AnimeRepository $animeRepository
     * @param ModifyAnimeRepository $modifyAnimeRepository
     * @param AddAnimeRepository $addAnimeRepository
     * @param DeleteAnimeRepository $deleteAnimeRepository
     * @param CastRepository $castRepository
     * @param ModifyCastRepository $modifyCastRepository
     * @param AddCastRepository $addCastRepository
     * @param DeleteCastRepository $deleteCastRepository
     * @param CreaterRepository $createrRepository
     * @param AddCreaterRepository $addCreaterRepository
     * @param DeleteCreaterRepository $deleteCreaterRepository
     * @param CompanyRepository $companyRepository
     * @param DeleteCompanyRepository $deleteCompanyRepository
     * @param OccupationRepository $occupationRepository
     * @param AnimeCreaterRepository $animeCreaterRepository
     * @return void
     */
    public function __construct(
        AnimeRepository $animeRepository,
        ModifyAnimeRepository $modifyAnimeRepository,
        AddAnimeRepository $addAnimeRepository,
        DeleteAnimeRepository $deleteAnimeRepository,
        CastRepository $castRepository,
        ModifyCastRepository $modifyCastRepository,
        AddCastRepository $addCastRepository,
        DeleteCastRepository $deleteCastRepository,
        CreaterRepository $createrRepository,
        ModifyCreaterRepository $modifyCreaterRepository,
        AddCreaterRepository $addCreaterRepository,
        DeleteCreaterRepository $deleteCreaterRepository,
        CompanyRepository $companyRepository,
        DeleteCompanyRepository $deleteCompanyRepository,
        OccupationRepository $occupationRepository,
        AnimeCreaterRepository $animeCreaterRepository,
    ) {
        $this->animeRepository = $animeRepository;
        $this->modifyAnimeRepository = $modifyAnimeRepository;
        $this->addAnimeRepository = $addAnimeRepository;
        $this->deleteAnimeRepository = $deleteAnimeRepository;
        $this->castRepository = $castRepository;
        $this->modifyCastRepository = $modifyCastRepository;
        $this->addCastRepository = $addCastRepository;
        $this->deleteCastRepository = $deleteCastRepository;
        $this->createrRepository = $createrRepository;
        $this->modifyCreaterRepository = $modifyCreaterRepository;
        $this->addCreaterRepository = $addCreaterRepository;
        $this->deleteCreaterRepository = $deleteCreaterRepository;
        $this->companyRepository = $companyRepository;
        $this->deleteCompanyRepository = $deleteCompanyRepository;
        $this->occupationRepository = $occupationRepository;
        $this->animeCreaterRepository = $animeCreaterRepository;
    }

    /**
     * アニメの基本情報変更申請データリストをアニメと共に取得
     *
     * @return Collection<int,ModifyAnime> | Collection<null>
     */
    public function getModifyAnimeRequestListWithAnimeWithCompanies()
    {
        return $this->modifyAnimeRepository->getModifyAnimeRequestListWithAnimeWithCompanies();
    }

    /**
     * modify_anime_idからアニメの基本情報変更申請データを取得
     *
     * @param int $modify_anime_id
     * @return ModifyAnime
     */
    public function getModifyAnimeRequest($modify_anime_id)
    {
        return $this->modifyAnimeRepository->getById($modify_anime_id);
    }

    /**
     * アニメの基本情報変更申請データを作成
     *
     * @param int $anime_id
     * @param AnimeRequest $request
     * @return void
     */
    public function createModifyAnimeRequest($anime_id, AnimeRequest $request)
    {
        $anime = $this->animeRepository->getById($anime_id);
        $this->animeRepository->createModifyAnimeRequest($anime, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * アニメの基本情報変更申請データからアニメの基本情報を更新
     *
     * @param int $modify_anime_id
     * @param AnimeRequest $request
     * @return void
     */
    public function updateAnimeInformationByRequest($modify_anime_id, AnimeRequest $request)
    {
        $anime = $this->animeRepository->getAnimeByModifyAnimeId($modify_anime_id);
        DB::transaction(function () use ($anime, $modify_anime_id, $request) {
            $this->animeRepository->deleteAnimeCompanyOfAnime($anime);
            $this->createAnimeCompanyByRequest($anime, $request);
            $this->animeRepository->updateInformationByRequest($anime, $request);
            $this->modifyAnimeRepository->deleteById($modify_anime_id);
        });
    }

    /**
     * リクエストによって会社に制作アニメを追加
     *
     * @param Anime $anime
     * @param AnimeRequest $request
     * @return void
     */
    public function createAnimeCompanyByRequest(Anime $anime, AnimeRequest $request)
    {
        $req_companies = $request->only(['company1', 'company2', 'company3']);
        $req_companies = array_filter($req_companies);
        foreach ($req_companies as $req_company) {
            $company = $this->companyRepository->getByName($req_company) ??
            $this->companyRepository->createByName($req_company);
            $this->companyRepository->createAnimeCompanyByAnimeAndCompany($anime, $company);
        }
    }

    /**
     * アニメの基本情報変更申請データを削除
     *
     * @param int $modify_anime_id
     * @return void
     */
    public function rejectModifyAnimeRequest($modify_anime_id)
    {
        $this->modifyAnimeRepository->deleteById($modify_anime_id);
    }

    /**
     * アニメの追加申請リストを取得
     *
     * @return Collection<int,AddAnime> | Collection<null>
     */
    public function getAddAnimeRequestListDeleteUnFlag()
    {
        return $this->addAnimeRepository->getDeleteUnFlag();
    }

    /**
     * アニメの追加申請データを作成
     *
     * @param AnimeRequest $request
     * @return void
     */
    public function createAddAnimeRequest(AnimeRequest $request)
    {
        $this->addAnimeRepository->createAddAnimeRequest($request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * アニメの追加申請からアニメを追加
     *
     * @param int $add_anime_id
     * @param AnimeRequest $request
     * @return void
     */
    public function createAnimeByRequest($add_anime_id, AnimeRequest $request)
    {
        // 存在しなければNot Found
        $this->addAnimeRepository->getById($add_anime_id);
        DB::transaction(function () use ($add_anime_id, $request) {
            $anime = $this->animeRepository->createByRequest($request);
            $this->createAnimeCompanyByRequest($anime, $request);
            $this->addAnimeRepository->updateAddAnimeRequest($add_anime_id, $request, $anime);
        });
    }

    /**
     * アニメの追加申請を却下
     *
     * @param int $add_anime_id
     * @return void
     */
    public function rejectAddAnimeRequest($add_anime_id)
    {
        $this->addAnimeRepository->deleteById($add_anime_id);
    }


    /**
     * アニメの削除申請リストを取得
     *
     * @return Collection<int,DeleteAnime> | Collection<null>
     */
    public function getDeleteAnimeRequestListWithAnime()
    {
        return $this->deleteAnimeRepository->getDeleteAnimeRequestListWithAnime();
    }

    /**
     * アニメの削除申請を作成
     *
     * @param int $anime_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteAnimeRequest($anime_id, DeleteRequest $request)
    {
        $this->animeRepository->getById($anime_id);
        $this->deleteAnimeRepository->createDeleteAnimeRequest($anime_id, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * アニメの削除申請からアニメを削除
     *
     * @param int $delete_anime_id
     * @return void
     */
    public function deleteAnimeByRequest($delete_anime_id)
    {
        $anime = $this->animeRepository->getAnimeByDeleteAnimeId($delete_anime_id);
        DB::transaction(function () use ($anime, $delete_anime_id) {
            $this->deleteAnimeRepository->deleteById($delete_anime_id);
            $this->animeRepository->deleteById($anime->id);
        });
    }

    /**
     * アニメの削除申請を却下
     *
     * @param int $delete_anime_id
     * @return void
     */
    public function rejectDeleteAnimeRequest($delete_anime_id)
    {
        $this->deleteAnimeRepository->deleteById($delete_anime_id);
    }

    /**
     * 声優の情報変更申請データリストを取得
     *
     * @return Collection<int,ModifyCast> | Collection<null>
     */
    public function getModifyCastRequestListWithCast()
    {
        return $this->modifyCastRepository->getModifyCastRequestListWithCast();
    }

    /**
     * 声優の情報変更申請データを作成
     *
     * @param int $cast_id
     * @param CastRequest $request
     * @return void
     */
    public function createModifyCastRequest($cast_id, CastRequest $request)
    {
        $cast = $this->castRepository->getById($cast_id);
        $this->castRepository->createModifyCastRequest($cast, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * 声優情報変更申請データからアニメの基本情報を更新
     *
     * @param int $modify_cast_id
     * @param CastRequest $request
     * @return void
     */
    public function updateCastInformationByRequest($modify_cast_id, CastRequest $request)
    {
        $cast = $this->castRepository->getCastByModifyCastId($modify_cast_id);
        DB::transaction(function () use ($cast, $modify_cast_id, $request) {
            $this->castRepository->updateInformationByRequest($cast, $request);
            $this->modifyCastRepository->deleteById($modify_cast_id);
        });
    }

    /**
     * 声優情報変更申請データを削除
     *
     * @param int $modify_cast_id
     * @return void
     */
    public function rejectModifyCastRequest($modify_cast_id)
    {
        $this->modifyCastRepository->deleteById($modify_cast_id);
    }

    /**
     * 声優の追加申請リストを取得
     *
     * @return Collection<int,AddCast> | Collection<null>
     */
    public function getAddCastRequestListDeleteUnFlag()
    {
        return $this->addCastRepository->getDeleteUnFlag();
    }

    /**
     * 声優の追加申請データを作成
     *
     * @param CastRequest $request
     * @return void
     */
    public function createAddCastRequest(CastRequest $request)
    {
        $this->addCastRepository->createAddCastRequest($request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * 声優の追加申請から声優を追加
     *
     * @param int $add_cast_id
     * @param CastRequest $request
     * @return void
     */
    public function createCastByRequest($add_cast_id, CastRequest $request)
    {
        // 存在しなければNot Found
        $this->addCastRepository->getById($add_cast_id);
        DB::transaction(function () use ($add_cast_id, $request) {
            $cast = $this->castRepository->createByRequest($request);
            $this->addCastRepository->updateAddCastRequest($add_cast_id, $request, $cast);
        });
    }

    /**
     * 声優の追加申請を却下
     *
     * @param int $add_cast_id
     * @return void
     */
    public function rejectAddCastRequest($add_cast_id)
    {
        $this->addCastRepository->deleteById($add_cast_id);
    }

    /**
     * 声優の削除申請リストを取得
     *
     * @return Collection<int,DeleteCast> | Collection<null>
     */
    public function getDeleteCastRequestListWithCast()
    {
        return $this->deleteCastRepository->getDeleteCastRequestListWithCast();
    }

    /**
     * 声優の削除申請を作成
     *
     * @param int $cast_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteCastRequest($cast_id, DeleteRequest $request)
    {
        $this->castRepository->getById($cast_id);
        $this->deleteCastRepository->createDeleteCastRequest($cast_id, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * 声優の削除申請からアニメを削除
     *
     * @param int $delete_cast_id
     * @return void
     */
    public function deleteCastByRequest($delete_cast_id)
    {
        $cast = $this->castRepository->getCastByDeleteCastId($delete_cast_id);
        DB::transaction(function () use ($cast, $delete_cast_id) {
            $this->deleteCastRepository->deleteById($delete_cast_id);
            $this->castRepository->deleteById($cast->id);
        });
    }

    /**
     * 声優の削除申請を却下
     *
     * @param int $delete_cast_id
     * @return void
     */
    public function rejectDeleteCastRequest($delete_cast_id)
    {
        $this->deleteCastRepository->deleteById($delete_cast_id);
    }

    /**
     * クリエイターの情報変更申請データリストを取得
     *
     * @return Collection<int,ModifyCreater> | Collection<null>
     */
    public function getModifyCreaterRequestListWithCreater()
    {
        return $this->modifyCreaterRepository->getModifyCreaterRequestListWithCreater();
    }

    /**
     * クリエイターの情報変更申請データを作成
     *
     * @param int $creater_id
     * @param CreaterRequest $request
     * @return void
     */
    public function createModifyCreaterRequest($creater_id, CreaterRequest $request)
    {
        $creater = $this->createrRepository->getById($creater_id);
        $this->createrRepository->createModifyCreaterRequest($creater, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * クリエイター情報変更申請データからアニメの基本情報を更新
     *
     * @param int $modify_creater_id
     * @param CreaterRequest $request
     * @return void
     */
    public function updateCreaterInformationByRequest($modify_creater_id, CreaterRequest $request)
    {
        $creater = $this->createrRepository->getCreaterByModifyCreaterId($modify_creater_id);
        DB::transaction(function () use ($creater, $modify_creater_id, $request) {
            $this->createrRepository->updateInformationByRequest($creater, $request);
            $this->modifyCreaterRepository->deleteById($modify_creater_id);
        });
    }

    /**
     * クリエイター情報変更申請データを削除
     *
     * @param int $modify_creater_id
     * @return void
     */
    public function rejectModifyCreaterRequest($modify_creater_id)
    {
        $this->modifyCreaterRepository->deleteById($modify_creater_id);
    }

    /**
     * クリエイターの追加申請リストを取得
     *
     * @return Collection<int,AddCreater> | Collection<null>
     */
    public function getAddCreaterRequestListDeleteUnFlag()
    {
        return $this->addCreaterRepository->getDeleteUnFlag();
    }

    /**
     * クリエイターの追加申請データを作成
     *
     * @param CreaterRequest $request
     * @return void
     */
    public function createAddCreaterRequest(CreaterRequest $request)
    {
        $this->addCreaterRepository->createAddCreaterRequest($request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * クリエイターの追加申請からクリエイターを追加
     *
     * @param int $add_creater_id
     * @param CreaterRequest $request
     * @return void
     */
    public function createCreaterByRequest($add_creater_id, CreaterRequest $request)
    {
        // 存在しなければNot Found
        $this->addCreaterRepository->getById($add_creater_id);
        DB::transaction(function () use ($add_creater_id, $request) {
            $creater = $this->createrRepository->createByRequest($request);
            $this->addCreaterRepository->updateAddCreaterRequest($add_creater_id, $request, $creater);
        });
    }

    /**
     * クリエイターの追加申請を却下
     *
     * @param int $add_creater_id
     * @return void
     */
    public function rejectAddCreaterRequest($add_creater_id)
    {
        $this->addCreaterRepository->deleteById($add_creater_id);
    }

    /**
     * クリエイターの削除申請リストを取得
     *
     * @return Collection<int,DeleteCreater> | Collection<null>
     */
    public function getDeleteCreaterRequestListWithCreater()
    {
        return $this->deleteCreaterRepository->getDeleteCreaterRequestListWithCreater();
    }

    /**
     * クリエイターの削除申請を作成
     *
     * @param int $creater_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteCreaterRequest($creater_id, DeleteRequest $request)
    {
        $this->createrRepository->getById($creater_id);
        $this->deleteCreaterRepository->createDeleteCreaterRequest($creater_id, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * クリエイターの削除申請からアニメを削除
     *
     * @param int $delete_creater_id
     * @return void
     */
    public function deleteCreaterByRequest($delete_creater_id)
    {
        $creater = $this->createrRepository->getCreaterByDeleteCreaterId($delete_creater_id);
        DB::transaction(function () use ($creater, $delete_creater_id) {
            $this->deleteCreaterRepository->deleteById($delete_creater_id);
            $this->createrRepository->deleteById($creater->id);
        });
    }

    /**
     * クリエイターの削除申請を却下
     *
     * @param int $delete_creater_id
     * @return void
     */
    public function rejectDeleteCreaterRequest($delete_creater_id)
    {
        $this->deleteCreaterRepository->deleteById($delete_creater_id);
    }

    /**
     * 会社の削除申請リストを取得
     *
     * @return Collection<int,DeleteCompany> | Collection<null>
     */
    public function getDeleteCompanyRequestListWithCompany()
    {
        return $this->deleteCompanyRepository->getDeleteCompanyRequestListWithCompany();
    }

    /**
     * 会社の削除申請を作成
     *
     * @param int $company_id
     * @param DeleteRequest $request
     * @return void
     */
    public function createDeleteCompanyRequest($company_id, DeleteRequest $request)
    {
        $this->companyRepository->getById($company_id);
        $this->deleteCompanyRepository->createDeleteCompanyRequest($company_id, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * 会社の削除申請からアニメを削除
     *
     * @param int $delete_company_id
     * @return void
     */
    public function deleteCompanyByRequest($delete_company_id)
    {
        $company = $this->companyRepository->getCompanyByDeleteCompanyId($delete_company_id);
        DB::transaction(function () use ($company, $delete_company_id) {
            $this->deleteCompanyRepository->deleteById($delete_company_id);
            $this->companyRepository->deleteById($company->id);
        });
    }

    /**
     * 会社の削除申請を却下
     *
     * @param int $delete_company_id
     * @return void
     */
    public function rejectDeleteCompanyRequest($delete_company_id)
    {
        $this->deleteCompanyRepository->deleteById($delete_company_id);
    }

    /**
     * アニメの出演声優をリクエストから作成または削除または変更
     *
     * @param int $anime_id
     * @param Request $request
     * @return void
     */
    public function createOrDeleteOrModifyOccupations($anime_id, $request)
    {
        foreach ($request->modify_type as $key => $modify_type) {
            if (is_null($request->cast_id[$key])) {
                continue;
            }
            if ($modify_type == 'no_change') {
                continue;
            }
            if ($modify_type == 'change') {
                $this->occupationRepository->getById($request->occupation_id[$key]);
                $this->occupationRepository->modifyOccupationByRequst($request, $key);
            }
            if ($modify_type == 'delete') {
                $this->occupationRepository->getById($request->occupation_id[$key]);
                $this->occupationRepository->deleteById($request->occupation_id[$key]);
            }
            if ($modify_type == 'add') {
                $this->castRepository->getById($request->cast_id[$key]);
                $this->occupationRepository->createOccupationByRequest($anime_id, $request, $key);
            }
        }
    }

    /**
     * アニメのクリエイターをリクエストから作成または削除または変更
     *
     * @param int $anime_id
     * @param Request $request
     * @return void
     */
    public function createOrDeleteOrModifyAnimeCreaters($anime_id, $request)
    {
        foreach ($request->modify_type as $key => $modify_type) {
            if (is_null($request->creater_id[$key])) {
                continue;
            }
            if ($modify_type == 'no_change') {
                continue;
            }
            if ($modify_type == 'change') {
                $this->animeCreaterRepository->getById($request->anime_creater_id[$key]);
                $this->animeCreaterRepository->modifyAnimeCreaterByRequst($request, $key);
            }
            if ($modify_type == 'delete') {
                $this->animeCreaterRepository->getById($request->anime_creater_id[$key]);
                $this->animeCreaterRepository->deleteById($request->anime_creater_id[$key]);
            }
            if ($modify_type == 'add') {
                $this->createrRepository->getById($request->creater_id[$key]);
                $this->animeCreaterRepository->createAnimeCreaterByRequest($anime_id, $request, $key);
            }
        }
    }

    /**
     * アニメの追加リストを取得
     *
     * @return Collection<int,AddAnime> | Collection<null>
     */
    public function getAddAnimeListLatest()
    {
        return $this->addAnimeRepository->getDeleteFlagLatest();
    }

    /**
     * 声優の追加リストを取得
     *
     * @return Collection<int,AddCast> | Collection<null>
     */
    public function getAddCastListLatest()
    {
        return $this->addCastRepository->getDeleteFlagLatest();
    }

    /**
     * クリエイターの追加リストを取得
     *
     * @return Collection<int,AddCreater> | Collection<null>
     */
    public function getAddCreaterListLatest()
    {
        return $this->addCreaterRepository->getDeleteFlagLatest();
    }

    /**
     * アニメを削除
     *
     * @param int $anime_id
     * @return void
     */
    public function deleteAnime($anime_id)
    {
        $this->animeRepository->deleteById($anime_id);
    }

    /**
     * 変更申請時管理者にメールで通知
     *
     * @return void
     */
    public function sendMailWhenModifyRequest()
    {
        if (env('APP_ENV') == 'production' && Gate::denies('isAdmin')) {
            $data = [];

            Mail::send('emails.modify_email', $data, function ($message) {
                $message->to(config('mail.from.address'), config('app.name'))
                ->subject('変更申請');
            });
        }
    }
}
