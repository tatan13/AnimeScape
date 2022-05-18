<?php

namespace App\Services;

use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use App\Models\ModifyCast;
use App\Models\DeleteAnime;
use App\Models\AddAnime;
use App\Models\DeleteCast;
use App\Models\Anime;
use App\Models\Cast;
use App\Repositories\ModifyAnimeRepository;
use App\Repositories\ModifyOccupationRepository;
use App\Repositories\ModifyCastRepository;
use App\Repositories\AnimeRepository;
use App\Repositories\DeleteAnimeRepository;
use App\Repositories\AddAnimeRepository;
use App\Repositories\DeleteCastRepository;
use App\Repositories\CastRepository;
use App\Repositories\OccupationRepository;
use Illuminate\Http\Request;
use App\Http\Requests\AnimeRequest;
use App\Http\Requests\ModifyCastRequest;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\DeleteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Mail;

class ModifyService
{
    private ModifyAnimeRepository $modifyAnimeRepository;
    private ModifyOccupationRepository $modifyOccupationRepository;
    private ModifyCastRepository $modifyCastRepository;
    private AnimeRepository $animeRepository;
    private DeleteAnimeRepository $deleteAnimeRepository;
    private AddAnimeRepository $addAnimeRepository;
    private DeleteCastRepository $deleteCastRepository;
    private CastRepository $castRepository;
    private OccupationRepository $occupationRepository;

    /**
     * コンストラクタ
     *
     * @param ModifyAnimeRepository $modifyAnimeRepository
     * @param ModifyOccupationRepository $modifyOccupationRepository
     * @param ModifyCastRepository $modifyCastRepository
     * @param AnimeRepository $animeRepository
     * @param DeleteAnimeRepository $deleteAnimeRepository
     * @param AddAnimeRepository $addAnimeRepository
     * @param DeleteCastRepository $deleteCastRepository
     * @param CastRepository $castRepository
     * @param OccupationRepository $occupationRepository
     * @return void
     */
    public function __construct(
        ModifyAnimeRepository $modifyAnimeRepository,
        ModifyOccupationRepository $modifyOccupationRepository,
        ModifyCastRepository $modifyCastRepository,
        AnimeRepository $animeRepository,
        DeleteAnimeRepository $deleteAnimeRepository,
        AddAnimeRepository $addAnimeRepository,
        DeleteCastRepository $deleteCastRepository,
        CastRepository $castRepository,
        OccupationRepository $occupationRepository,
    ) {
        $this->modifyAnimeRepository = $modifyAnimeRepository;
        $this->modifyOccupationRepository = $modifyOccupationRepository;
        $this->modifyCastRepository = $modifyCastRepository;
        $this->animeRepository = $animeRepository;
        $this->deleteAnimeRepository = $deleteAnimeRepository;
        $this->addAnimeRepository = $addAnimeRepository;
        $this->deleteCastRepository = $deleteCastRepository;
        $this->castRepository = $castRepository;
        $this->occupationRepository = $occupationRepository;
    }

    /**
     * modify_anime_idからアニメの基本情報修正申請データを取得
     *
     * @param int $modify_anime_id
     * @return ModifyAnime
     */
    public function getModifyAnimeRequest($modify_anime_id)
    {
        return $this->modifyAnimeRepository->getById($modify_anime_id);
    }

    /**
     * アニメの基本情報修正申請データを作成
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
     * アニメの基本情報修正申請データからアニメの基本情報を更新
     *
     * @param int $modify_anime_id
     * @param AnimeRequest $request
     * @return void
     */
    public function updateAnimeInformationByRequest($modify_anime_id, AnimeRequest $request)
    {
        $anime = $this->animeRepository->getAnimeByModifyAnimeId($modify_anime_id);
        DB::transaction(function () use ($anime, $modify_anime_id, $request) {
            $this->animeRepository->updateInformationByRequest($anime, $request);
            $this->modifyAnimeRepository->deleteById($modify_anime_id);
        });
    }

    /**
     * アニメの基本情報修正申請データを削除
     *
     * @param int $modify_anime_id
     * @return void
     */
    public function rejectModifyAnimeRequest($modify_anime_id)
    {
        $this->modifyAnimeRepository->deleteById($modify_anime_id);
    }

    /**
     * アニメの基本情報修正申請データリストをアニメと共に取得
     *
     * @return Collection<int,ModifyAnime> | Collection<null>
     */
    public function getModifyAnimeRequestListWithAnime()
    {
        return $this->modifyAnimeRepository->getModifyAnimeRequestListWithAnime();
    }

    /**
     * アニメの出演声優変更申請データを削除
     *
     * @param int $anime_id
     * @return void
     */
    public function rejectModifyOccupationsRequestOfAnimeId($anime_id)
    {
        $this->animeRepository->getById($anime_id);
        $this->modifyOccupationRepository->deleteModifyOccupationsRequestOfAnimeId($anime_id);
    }

    /**
     * アニメの出演声優変更申請データを作成
     *
     * @param int $anime_id
     * @param Request $request
     * @return void
     */
    public function createModifyOccupationsRequest($anime_id, Request $request)
    {
        $this->animeRepository->getById($anime_id);
        $req_casts = $this->filterRequest($request);
        $modify_occupation_request_list =
        $this->modifyOccupationRepository->getModifyOccupationsRequestOfAnimeId($anime_id);
        foreach ($req_casts as $req_cast) {
            if (!$this->isContainCastName($modify_occupation_request_list, $req_cast)) {
                $this->modifyOccupationRepository->createModifyOccupationRequest($anime_id, $req_cast);
            }
        }
        $this->sendMailWhenModifyRequest();
    }

    /**
     * アニメの出演声優を更新
     *
     * @param int $anime_id
     * @param Request $request
     * @return void
     */
    public function updateAnimeCastsByRequest($anime_id, Request $request)
    {
        $this->animeRepository->getById($anime_id);
        $req_casts = $this->filterRequest($request);
        DB::transaction(function () use ($anime_id, $req_casts) {
            $this->occupationRepository->deleteOccupationsOfAnimeId($anime_id);
            foreach ($req_casts as $req_cast) {
                $cast = $this->castRepository->getCastByName($req_cast) ??
                $this->castRepository->createByName($req_cast);
                $this->castRepository->createOccupationByCastAndAnimeId($cast, $anime_id);
            }
            $this->modifyOccupationRepository->deleteModifyOccupationsRequestOfAnimeId($anime_id);
        });
    }

    /**
     * リクエストのトークンとnull値を削除
     *
     * @param Request $request
     * @return array<int, string>
     */
    public function filterRequest(Request $request)
    {
        $request = $request->except('_token');
        return array_filter($request);
    }

    /**
     * コレクション内に$cast_nameが含まれているか判定
     *
     * @param Collection<int,ModifyOccupation> $list
     * @param string $cast_name
     * @return bool
     */
    public function isContainCastName(Collection $list, string $cast_name)
    {
        return $list->contains('cast_name', $cast_name);
    }

    /**
     * 変更申請時管理者にメールで通知
     *
     * @return void
     */
    public function sendMailWhenModifyRequest()
    {
        if (env('APP_ENV') == 'production') {
            $data = [];

            Mail::send('emails.modify_email', $data, function ($message) {
                $message->to(config('mail.from.address'), config('app.name'))
                ->subject('変更申請');
            });
        }
    }

    /**
     * 声優の情報修正申請データを作成
     *
     * @param int $cast_id
     * @param ModifyCastRequest $request
     * @return void
     */
    public function createModifyCastRequest($cast_id, ModifyCastRequest $request)
    {
        $cast = $this->castRepository->getById($cast_id);
        $this->castRepository->createModifyCastRequest($cast, $request);
        $this->sendMailWhenModifyRequest();
    }

    /**
     * 声優の情報修正申請データリストを取得
     *
     * @return Collection<int,ModifyCast> | Collection<null>
     */
    public function getModifyCastRequestListWithCast()
    {
        return $this->modifyCastRepository->getModifyCastRequestListWithCast();
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
     * modify_cast_idから声優情報修正申請データを取得
     *
     * @param int $modify_cast_id
     * @return ModifyCast
     */
    public function getModifyCastRequest($modify_cast_id)
    {
        return $this->modifyCastRepository->getById($modify_cast_id);
    }

    /**
     * 声優情報修正申請データからアニメの基本情報を更新
     *
     * @param int $modify_cast_id
     * @param ModifyCastRequest $request
     * @return void
     */
    public function updateCastInformationByRequest($modify_cast_id, ModifyCastRequest $request)
    {
        $cast = $this->castRepository->getCastByModifyCastId($modify_cast_id);
        DB::transaction(function () use ($cast, $modify_cast_id, $request) {
            $this->castRepository->updateInformationByRequest($cast, $request);
            $this->modifyCastRepository->deleteById($modify_cast_id);
        });
    }

    /**
     * 声優情報修正申請データを削除
     *
     * @param int $modify_cast_id
     * @return void
     */
    public function rejectModifyCastRequest($modify_cast_id)
    {
        $this->modifyCastRepository->deleteById($modify_cast_id);
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
     * アニメの追加申請リストを取得
     *
     * @return Collection<int,DeleteAnime> | Collection<null>
     */
    public function getAddAnimeRequestList()
    {
        return $this->addAnimeRepository->getAll();
    }

    /**
     * アニメの追加申請からアニメを追加
     *
     * @param int $add_anime_id
     * @param AnimeRequest $request
     * @return void
     */
    public function addAnimeByRequest($add_anime_id, AnimeRequest $request)
    {
        DB::transaction(function () use ($add_anime_id, $request) {
            $this->animeRepository->addByRequest($request);
            $this->addAnimeRepository->deleteById($add_anime_id);
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
     * 声優の削除申請リストを取得
     *
     * @return Collection<int,DeleteCast> | Collection<null>
     */
    public function getDeleteCastRequestListWithCast()
    {
        return $this->deleteCastRepository->getDeleteCastRequestListWithCast();
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
}
