<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository extends AbstractRepository
{
    /**
     * モデル名を取得
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return Item::class;
    }

    /**
     * 商品をアニメによって取得
     *
     * @param Anime $anime
     * @return Collection<int,Item>
     */
    public function getItemsForAnime($anime)
    {
        return Item::where('anime_id', $anime->id)->oldest('category')->oldest('number')->get();
    }
}
