<?php

namespace App\Repositories;

use App\Models\AddAnime;
use App\Http\Requests\AnimeRequest;
use Illuminate\Database\Eloquent\Collection;

class AddAnimeRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return AddAnime::class;
    }

    /**
     * 削除フラグの立っていないアニメの追加申請リストを取得
     *
     * @return Collection<int,AddAnime> | Collection<null>
     */
    public function getDeleteUnFlag()
    {
        return AddAnime::where('delete_flag', 0)->get();
    }

    /**
     * 削除フラグの立っているアニメの追加申請リストを降順に取得
     *
     * @return Collection<int,AddAnime> | Collection<null>
     */
    public function getDeleteFlagLatest()
    {
        return AddAnime::where('delete_flag', 1)->latest('updated_at')->get();
    }

    /**
     * アニメの追加申請データを作成
     *
     * @param AnimeRequest $request
     * @return void
     */
    public function createAddAnimeRequest(AnimeRequest $request)
    {
        AddAnime::create($request->validated());
    }

    /**
     * アニメの追加申請に削除フラグを立てて更新
     *
     * @param int $add_anime_id
     * @param AnimeRequest $request
     * @return void
     */
    public function updateAddAnimeRequest($add_anime_id, AnimeRequest $request)
    {
        AddAnime::where('id', $add_anime_id)->update(array_merge($request->validated(), ['delete_flag' => 1]));
    }
}
