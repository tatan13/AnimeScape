<?php

namespace App\Services;

use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use App\Models\Anime;
use App\Models\Cast;
use App\Repositories\ModifyAnimeRepository;
use App\Repositories\ModifyOccupationRepository;
use App\Repositories\AnimeRepository;
use App\Repositories\CastRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ModifyAnimeRequest;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class ModifyService
{
    private ModifyAnimeRepository $modifyAnimeRepository;
    private ModifyOccupationRepository $modifyOccupationRepository;
    private AnimeRepository $animeRepository;
    private CastRepository $castRepository;

    /**
     * コンストラクタ
     *
     * @param ModifyAnimeRepository $modifyAnimeRepository
     * @param ModifyOccupationRepository $modifyOccupationRepository
     * @param AnimeRepository $animeRepository
     * @param CastRepository $castRepository
     * @return void
     */
    public function __construct(
        ModifyAnimeRepository $modifyAnimeRepository,
        ModifyOccupationRepository $modifyOccupationRepository,
        AnimeRepository $animeRepository,
        CastRepository $castRepository,
    ) {
        $this->modifyAnimeRepository = $modifyAnimeRepository;
        $this->modifyOccupationRepository = $modifyOccupationRepository;
        $this->animeRepository = $animeRepository;
        $this->castRepository = $castRepository;
    }

    /**
     * idからアニメの基本情報修正申請データを取得
     *
     * @param int $id
     * @return ModifyAnime
     */
    public function getModifyAnime($id)
    {
        return $this->modifyAnimeRepository->getById($id);
    }

    /**
     * アニメの基本情報修正申請データを作成
     *
     * @param Anime $anime
     * @param ModifyAnimeRequest $request
     * @return void
     */
    public function createModifyAnime(Anime $anime, ModifyAnimeRequest $request)
    {
        $this->modifyAnimeRepository->createModifyAnime($anime, $request);
    }

    /**
     * アニメの基本情報修正申請データからアニメの基本情報を更新
     *
     * @param ModifyAnime $modify_anime
     * @param ModifyAnimeRequest $request
     * @return void
     */
    public function updateAnimeInfoBy(ModifyAnime $modify_anime, ModifyAnimeRequest $request)
    {
        $anime = $this->modifyAnimeRepository->getAnime($modify_anime);
        DB::transaction(function () use ($anime, $modify_anime, $request) {
            $this->animeRepository->updateInformation($anime, $request);
            $this->modifyAnimeRepository->delete($modify_anime);
        });
    }

    /**
     * アニメの基本情報修正申請データを削除
     *
     * @param ModifyAnime $modify_anime
     * @return void
     */
    public function deleteModifyAnime(ModifyAnime $modify_anime)
    {
        $this->modifyAnimeRepository->delete($modify_anime);
    }

    /**
     * アニメの基本情報修正申請データリストをアニメと共に取得
     *
     * @return Collection<int,ModifyAnime> | Collection<null>
     */
    public function getModifyAnimeListWithAnime()
    {
        return $this->modifyAnimeRepository->getModifyAnimeListWithAnime();
    }

    /**
     * アニメからアニメの出演声優変更申請データリストを取得
     *
     * @param Anime $anime
     * @return Collection<int,ModifyOccupation> | Collection<null>
     */
    public function getModifyOccupationListOfAnime(Anime $anime)
    {
        return $this->animeRepository->getModifyOccupationListOfAnime($anime);
    }

    /**
     * アニメの出演声優変更申請データを削除
     *
     * @param Anime $anime
     * @return void
     */
    public function deleteModifyOccupationsOfAnime(Anime $anime)
    {
        $this->animeRepository->deleteModifyOccupationsOfAnime($anime);
    }

    /**
     * アニメの出演声優変更申請データを作成
     *
     * @param Anime $anime
     * @param Request $request
     * @return void
     */
    public function createModifyOccupations(Anime $anime, Request $request)
    {
        $req_casts = $this->filterRequest($request);
        $modify_occupation_list = $this->animeRepository->getModifyOccupationListOfAnime($anime);
        foreach ($req_casts as $req_cast) {
            if (!$this->isContainCastName($modify_occupation_list, $req_cast)) {
                $this->modifyOccupationRepository->createModifyOccupation($anime, $req_cast);
            }
        }
    }

    /**
     * アニメの出演声優を更新
     *
     * @param Anime $anime
     * @param Request $request
     * @return void
     */
    public function updateAnimeCastsInfo(Anime $anime, Request $request)
    {
        $req_casts = $this->filterRequest($request);
        DB::transaction(function () use ($anime, $req_casts) {
            $this->animeRepository->deleteOccupations($anime);
            foreach ($req_casts as $req_cast) {
                $cast = $this->castRepository->getCastByName($req_cast) ?? $this->castRepository->create($req_cast);
                $this->castRepository->createOccupation($cast, $anime);
            }
            $this->animeRepository->deleteModifyOccupationsOfAnime($anime);
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
}