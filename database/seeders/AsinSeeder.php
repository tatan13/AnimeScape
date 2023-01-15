<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class AsinSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/2023_asin_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $anime_all = Anime::all();

        $item_all = Item::all();
        $upsert_item_list = [];

        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (empty($anime)) {
                echo $post->title;
            } else {
                foreach ($post->item_list as $item_json) {
                    $item = $item_all->where('asin', $item_json->asin)->first();
                    switch ($item_json->category) {
                        case 'BD':
                            $number = 1;
                            break;
                        case 'BD_BOX':
                            $number = 2;
                            break;
                        case 'DVD':
                            $number = 3;
                            break;
                        case 'CD':
                            $number = 4;
                            break;
                        case 'Comic':
                            $number = 5;
                            break;
                        case 'Novel':
                            $number = 6;
                            break;
                    }
                    if (is_null($item)) {
                        $upsert_item_list [] = [
                            'id' => null,
                            'title' => $item_json->name,
                            'anime_id' => $anime->id,
                            'category' => $number,
                            'site_id' => 1,
                            'url' => 'https://www.amazon.co.jp/dp/' . $item_json->asin . '/ref=nosim?tag=tatan13-22',
                            'number' => $item_json->number,
                        ];
                    } else {
                        $upsert_item_list [] = [
                            'id' => $item->id,
                            'title' => $item_json->name,
                            'anime_id' => $anime->id,
                            'category' => $number,
                            'site_id' => 1,
                            'url' => 'https://www.amazon.co.jp/dp/' . $item_json->asin . '/ref=nosim?tag=tatan13-22',
                            'number' => $item_json->number,
                        ];
                    }
                }
            }
        }

        Item::upsert($upsert_item_list, ['id']);
    }
}
