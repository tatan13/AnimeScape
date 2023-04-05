<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Occupation;
use App\Models\Creater;
use App\Models\AnimeCreater;
use App\Models\AddAnime;
use App\Models\AddCast;
use App\Models\AddCreater;
use Illuminate\Support\Facades\DB;

class SAnimeSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/second_shoboi_anime_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $anime_all = Anime::all();
        $upsert_anime_list = [];
        $upsert_add_anime_list = [];
        $add_anime_list = [];

        $cast_all = Cast::all();
        $upsert_cast_list = [];
        $upsert_add_cast_list = [];
        $add_cast_list = [];
        $upsert_act_cast_list = [];

        $creater_all = Creater::all();
        $upsert_creater_list = [];
        $upsert_add_creater_list = [];
        $add_creater_list = [];
        $upsert_anime_creater_list = [];


        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if ($post->add == 0) {
                if (!empty($anime)) {
                    $upsert_anime_list [] = [
                        'id' => $anime->id,
                        'title' => $anime->title,
                        'year' => $anime->year,
                        'coor' => $anime->coor,
                        'public_url' => is_null($anime->public_url) ? $post->public_url : $anime->public_url,
                        's_id' => is_null($anime->s_id) ? $post->tid : $anime->s_id,
                        'furigana' => is_null($anime->furigana) ? $post->furigana : $anime->furigana,
                        'number_of_episode' => is_null($anime->number_of_episode) ?
                        $post->number_of_episode : $anime->number_of_episode,
                        'twitter' => is_null($anime->twitter) ? $post->twitter : $anime->twitter,
                    ];
                }
            } else {
                if (empty($anime)) {
                    $upsert_anime_list [] = [
                        'id' => null,
                        'title' => $post->title,
                        'year' => $post->year,
                        'coor' => $post->coor,
                        'public_url' => $post->public_url,
                        's_id' => $post->tid,
                        'furigana' => $post->furigana,
                        'number_of_episode' => $post->number_of_episode,
                        'twitter' => $post->twitter,
                    ];
                    $add_anime_list [] = [
                        'title' => $post->title,
                    ];
                }
            }

            foreach ($post->casts as $cast_json) {
                $cast = $cast_all->where('name', $cast_json->name)->first();
                if (empty($cast)) {
                    $upsert_cast_list [] = [
                        'id' => null,
                        'name' => $cast_json->name,
                    ];
                    $add_cast_list [] = [
                        'name' => $cast_json->name,
                    ];
                }
            }

            foreach ($post->creater as $creater_json) {
                $creater = $creater_all->where('name', $creater_json->name)->first();
                if (empty($creater)) {
                    $upsert_creater_list [] = [
                        'id' => null,
                        'name' => $creater_json->name,
                    ];
                    $add_creater_list [] = [
                        'name' => $creater_json->name,
                    ];
                }
            }
        }


        DB::transaction(function () use (
            $upsert_anime_list,
            $add_anime_list,
            $upsert_add_anime_list,
            $anime_all
        ) {
            Anime::upsert($upsert_anime_list, ['id']);
            $anime_all = Anime::with(['creaters', 'actCasts'])->get();
            foreach ($add_anime_list as $add_anime) {
                $upsert_add_anime_list [] = [
                    'id' => null,
                    'anime_id' => $anime_all->where('title', $add_anime['title'])->first()->id,
                    'title' => $add_anime['title'],
                    'delete_flag' => 1,
                ];
            }
            AddAnime::upsert($upsert_add_anime_list, ['id']);
        });
        unset($upsert_anime_list);
        unset($upsert_add_anime_list);
        unset($add_anime_list);

        $upsert_cast_list = array_unique($upsert_cast_list, SORT_REGULAR);
        $add_cast_list = array_unique($add_cast_list, SORT_REGULAR);
        DB::transaction(function () use (
            $upsert_cast_list,
            $add_cast_list,
            $upsert_add_cast_list,
            $cast_all
        ) {
            Cast::upsert($upsert_cast_list, ['id']);
            $cast_all = Cast::all();
            foreach ($add_cast_list as $add_cast) {
                $upsert_add_cast_list [] = [
                    'id' => null,
                    'cast_id' => $cast_all->where('name', $add_cast['name'])->first()->id,
                    'name' => $add_cast['name'],
                    'delete_flag' => 1,
                ];
            }
            AddCast::upsert($upsert_add_cast_list, ['id']);
        });
        unset($upsert_cast_list);
        unset($upsert_add_cast_list);
        unset($add_cast_list);

        $upsert_creater_list = array_unique($upsert_creater_list, SORT_REGULAR);
        $add_creater_list = array_unique($add_creater_list, SORT_REGULAR);
        DB::transaction(function () use (
            $upsert_creater_list,
            $add_creater_list,
            $upsert_add_creater_list,
            $creater_all
        ) {
            Creater::upsert($upsert_creater_list, ['id']);
            $creater_all = Creater::all();
            foreach ($add_creater_list as $add_creater) {
                $upsert_add_creater_list [] = [
                    'id' => null,
                    'creater_id' => $creater_all->where('name', $add_creater['name'])->first()->id,
                    'name' => $add_creater['name'],
                    'delete_flag' => 1,
                ];
            }
            AddCreater::upsert($upsert_add_creater_list, ['id']);
        });
        unset($upsert_creater_list);
        unset($upsert_add_creater_list);
        unset($add_creater_list);

        $anime_all = Anime::all();
        $cast_all = Cast::all();
        $creater_all = Creater::all();
        $occupation_all = Occupation::all();
        $anime_creater_all = AnimeCreater::all();
        foreach ($posts as $key => $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (is_null($anime)) {
                echo($post->title);
            }
            foreach ($post->casts as $cast_json) {
                $cast = $cast_all->where('name', $cast_json->name)->first();
                if (is_null($cast)) {
                    echo($cast_json->name);
                }
                $occupation = $occupation_all->where('anime_id', $anime->id)->where('cast_id', $cast->id)->first();
                if (is_null($occupation)) {
                    $upsert_act_cast_list [] = [
                        'id' => null,
                        'anime_id' => $anime->id,
                        'cast_id' => $cast->id,
                        'character' => $cast_json->character,
                    ];
                } else {
                    $upsert_act_cast_list [] = [
                        'id' => $occupation->id,
                        'anime_id' => $anime->id,
                        'cast_id' => $cast->id,
                        'character' => is_null($occupation->character) ?
                        $cast_json->character : $occupation->character,
                    ];
                }
            }
            foreach ($post->creater as $creater_json) {
                $creater = $creater_all->where('name', $creater_json->name)->first();
                $anime_creaters = $anime_creater_all->where('anime_id', $anime->id)
                ->where('creater_id', $creater->id);
                if ($anime_creaters->isEmpty()) {
                    $upsert_anime_creater_list [] = [
                        'id' => null,
                        'anime_id' => $anime->id,
                        'creater_id' => $creater->id,
                        'classification' => $creater_json->classification,
                        'occupation' => $creater_json->occupation
                    ];
                } else {
                    foreach ($anime_creaters as $anime_creater) {
                        $upsert_anime_creater_list [] = [
                            'id' => $anime_creater->id,
                            'anime_id' => $anime->id,
                            'creater_id' => $creater->id,
                            'classification' => is_null($anime_creater->classification) ?
                            $creater_json->classification : $anime_creater->classification,
                            'occupation' => is_null($anime_creater->occupation) ?
                            $creater_json->occupation : $anime_creater->occupation,
                        ];
                    }
                }
            }
        }
        $upsert_act_cast_list_chunk = array_chunk($upsert_act_cast_list, 300);
        foreach ($upsert_act_cast_list_chunk as $upsert_act_cast_list) {
            Occupation::upsert($upsert_act_cast_list, ['id']);
        }
        $upsert_anime_creater_list_chunk = array_chunk($upsert_anime_creater_list, 300);
        foreach ($upsert_anime_creater_list_chunk as $upsert_anime_creater_list) {
            AnimeCreater::upsert($upsert_anime_creater_list, ['id']);
        }
    }
}
