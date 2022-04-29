<?php

namespace App\Repositories;

use App\Models\Cast;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Collection;

class CastRepository extends AbstractRepository
{
    /**
    * モデル名を取得
    *
    * @return string
    */
    public function getModelClass(): string
    {
        return Cast::class;
    }

    /**
     * 声優が出演するアニメリストを取得
     *
     * @param Cast $cast
     * @return Collection<int,Anime> | Collection<null>
     */
    public function getActAnimes(Cast $cast)
    {
        return $cast->actAnimes;
    }

    /**
     * 声優を作成
     *
     * @param string $cast_name
     * @return Cast
     */
    public function create(string $cast_name)
    {
        return Cast::create(['name' => $cast_name]);
    }

    /**
     * 名前から声優を取得
     *
     * @param string $cast_name
     * @return Cast | null
     */
    public function getCastByName($cast_name)
    {
        return Cast::where('name', $cast_name)->first();
    }
    /**
     * アニメの出演声優を作成
     *
     * @param Cast $cast
     * @param Anime $anime
     * @return void
     */
    public function createOccupation(Cast $cast, Anime $anime)
    {
        $cast->actAnimes()->attach($anime->id);
    }

    /**
     *
     */
    public function getBySearchWithactAnimes($search_word)
    {
        if (is_null($search_word)) {
            return array();
        }
        $casts = Cast::where(Cast::SEARCH_COLUMN, 'like', "%$search_word%")->with('actAnimes')->get();
        return $casts->isEmpty() ? array() : $casts;
    }
}
