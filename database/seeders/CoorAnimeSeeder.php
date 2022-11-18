<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\AddAnime;
use App\Models\Cast;
use App\Models\AddCast;
use App\Models\Company;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;

class CoorAnimeSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/2022_12_movie_list.json");
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

        $upsert_anime_company_list = [];

        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (empty($anime)) {
                $upsert_anime_list [] = [
                    'id' => null,
                    'title' => $post->title,
                    'year' => $post->year,
                    'coor' => $post->coor,
                    'public_url' => $post->public_url,
                    'title_short' => $post->title_short,
                    'twitter' => $post->twitter,
                    'hash_tag' => $post->hash_tag,
                    'media_category' => $post->media_category,
                ];
                $add_anime_list [] = [
                    'title' => $post->title,
                ];
            }
            if (!empty($post->casts)) {
                foreach ($post->casts as $cast_name) {
                    $cast = $cast_all->where('name', $cast_name->cast)->first();
                    if (empty($cast)) {
                        $upsert_cast_list [] = [
                            'id' => null,
                            'name' => $cast_name->cast,
                        ];
                        $add_cast_list [] = [
                            'name' => $cast_name->cast,
                        ];
                    }
                }
            }
        }
        Anime::upsert($upsert_anime_list, ['id']);
        unset($upsert_anime_list);

        $upsert_cast_list = array_unique($upsert_cast_list, SORT_REGULAR);
        Cast::upsert($upsert_cast_list, ['id']);
        unset($upsert_cast_list);

        $anime_all = Anime::with(['companies', 'actCasts'])->get();
        $cast_all = Cast::all();
        $company_all = Company::all();

        foreach ($add_anime_list as $add_anime) {
            $upsert_add_anime_list [] = [
                'id' => null,
                'anime_id' => $anime_all->where('title', $add_anime['title'])->first()->id,
                'title' => $add_anime['title'],
                'delete_flag' => 1,
            ];
        }
        AddAnime::upsert($upsert_add_anime_list, ['id']);
        unset($upsert_add_anime_list);
        unset($add_anime_list);

        $add_cast_list = array_unique($add_cast_list, SORT_REGULAR);
        foreach ($add_cast_list as $add_cast) {
            $upsert_add_cast_list [] = [
                'id' => null,
                'cast_id' => $cast_all->where('name', $add_cast['name'])->first()->id,
                'name' => $add_cast['name'],
                'delete_flag' => 1,
            ];
        }
        AddCast::upsert($upsert_add_cast_list, ['id']);
        unset($upsert_add_cast_list);
        unset($add_cast_list);

        foreach ($posts as $post) {
            if (!empty($post->casts)) {
                $anime = $anime_all->where('title', $post->title)->first();
                foreach ($post->casts as $cast_name) {
                    $cast = $cast_all->where('name', $cast_name->cast)->first();
                    if (!$anime->actCasts->contains('name', $cast->name)) {
                        $upsert_act_cast_list [] = [
                            'id' => null,
                            'anime_id' => $anime->id,
                            'cast_id' => $cast->id,
                            'character' => $cast_name->character,
                        ];
                    }
                }
            }
            if (!empty($post->company)) {
                $anime = $anime_all->where('title', $post->title)->first(); // @phpstan-ignore-line
                foreach ($post->company as $company_name) {
                    $company = $company_all->where('name', $company_name)->first();
                    if (is_null($company)) {
                        echo $anime->title . " " . $company_name . "\n";
                        continue;
                    }
                    if (!$anime->companies->contains('name', $company->name)) {
                        $upsert_anime_company_list [] = [
                            'id' => null,
                            'anime_id' => $anime->id,
                            'company_id' => $company->id,
                        ];
                    }
                }
            }
        }
        Occupation::upsert($upsert_act_cast_list, ['id']);
        DB::table('anime_company')->upsert($upsert_anime_company_list, ['id']);
    }
}
