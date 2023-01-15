<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Anime;
use App\Repositories\ItemRepository;
use Illuminate\Database\Eloquent\Collection;

class ItemService
{
    private ItemRepository $itemRepository;

    /**
     * コンストラクタ
     *
     * @param ItemRepository $itemRepository
     * @return void
     */
    public function __construct(
        ItemRepository $itemRepository,
    ) {
        $this->itemRepository = $itemRepository;
    }

    /**
     * 商品をidによって取得
     *
     * @param int $item_id
     * @return Item
     */
    public function getItemById($item_id)
    {
        return $this->itemRepository->getById($item_id);
    }

    /**
     * 商品をアニメによって取得
     *
     * @param Anime $anime
     * @return Collection<int,Item>
     */
    public function getItemsForAnime(Anime $anime)
    {
        return $this->itemRepository->getItemsForAnime($anime);
    }
}
