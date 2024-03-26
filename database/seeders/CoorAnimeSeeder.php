<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\AddAnime;
use App\Models\Cast;
use App\Models\AddCast;
use App\Models\Company;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoorAnimeSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/2024_2_anime_list.json");
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
                    'furigana' => $post->furigana,
                    'year' => $post->year,
                    'coor' => $post->coor,
                    'public_url' => $post->public_url,
                    // 'summary' => $post->story,
                    // 'title_short' => $post->title_short,
                    'twitter' => $post->twitter,
                    // 'hash_tag' => $post->hash_tag,
                    'media_category' => $post->media_category,
                    's_id' => $post->tid
                ];
                $add_anime_list [] = [
                    'title' => $post->title,
                ];
            }
            if (!empty($post->casts)) {
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
                foreach ($post->casts as $cast_json) {
                    $cast = $cast_all->where('name', $cast_json->name)->first();
                    if (!$anime->actCasts->contains('name', $cast->name)) {
                        $upsert_act_cast_list [] = [
                            'id' => null,
                            'anime_id' => $anime->id,
                            'cast_id' => $cast->id,
                            'character' => $cast_json->character,
                        ];
                    }
                }
            }
            if (!empty($post->company_list)) {
                $anime = $anime_all->where('title', $post->title)->first(); // @phpstan-ignore-line
                foreach ($post->company_list as $company_array) {
                    $company = $company_all->where('name', $company_array->name)->first();
                    if (is_null($company)) {
                        Log::channel('company_not_found')
                        ->info($anime->year . "年" . $anime->coor . "クール, アニメ: " .
                        $anime->title . " 制作会社: " . $company_array->name);
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
