<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;

class AnimesCastsTableSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/anime_cast_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $anime_all = Anime::all();
        $cast_all = Cast::all();
        $upsert_anime_list = [];
        $insert_cast_list = [];
        $insert_act_cast_list = [];

        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (empty($anime)) {
                $upsert_anime_list [] = [
                    'id' => null,
                    'title' => $post->title,
                    'year' => $post->year,
                    'coor' => $post->coor,
                    'company' => $post->company,
                ];
            } else {
                if (
                    $anime->year != $post->year ||
                    $anime->coor != $post->coor ||
                    $anime->company != $post->company
                ) {
                    $upsert_anime_list [] = [
                            'id' => $anime->id,
                            'title' => $post->title,
                            'year' => $post->year,
                            'coor' => $post->coor,
                            'company' => $post->company,
                        ];
                }
            }
            if (empty($post->casts)) {
                continue;
            }
            foreach ($post->casts as $cast_name) {
                $cast = $cast_all->where('name', $cast_name)->first();
                if (empty($cast)) {
                    $insert_cast_list [] = [
                            'name' => $cast_name,
                        ];
                }
            }
        }
        $insert_cast_list = array_unique($insert_cast_list, SORT_REGULAR);
        Anime::upsert($upsert_anime_list, ['id']);
        Cast::insert($insert_cast_list);

        $anime_all = Anime::with('actCasts')->get();
        $cast_all = Cast::all();
        foreach ($posts as $post) {
            if (empty($post->casts)) {
                continue;
            }
            $anime = $anime_all->where('title', $post->title)->first();
            foreach ($post->casts as $cast_name) {
                $cast = $cast_all->where('name', $cast_name)->first();
                if (!$anime->actCasts->contains('name', $cast->name)) {
                    $insert_act_cast_list [] = [
                        'anime_id' => $anime->id,
                        'cast_id' => $cast->id,
                    ];
                }
            }
        }
        Occupation::insert($insert_act_cast_list, ['id']);
    }
}
