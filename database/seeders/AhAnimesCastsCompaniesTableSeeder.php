<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Company;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;

class AhAnimesCastsCompaniesTableSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/AH_1965_1999_anime_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $anime_all = Anime::all();
        $upsert_anime_list = [];

        $cast_all = Cast::all();
        $insert_cast_list = [];
        $insert_act_cast_list = [];

        $company_all = Company::all();
        $insert_company_list = [];
        $insert_anime_company_list = [];

        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (empty($anime)) {
                $upsert_anime_list [] = [
                    'id' => null,
                    'title' => $post->title,
                    'year' => $post->year,
                    'coor' => $post->coor,
                    'public_url' => $post->public_url,
                ];
            } else {
                if (
                    $anime->year != $post->year ||
                    $anime->coor != $post->coor
                ) {
                    $upsert_anime_list [] = [
                            'id' => $anime->id,
                            'title' => $post->title,
                            'year' => is_null($anime->year) ? $post->year : $anime->year,
                            'coor' => is_null($anime->coor) ? $post->coor : $anime->coor,
                            'public_url' => is_null($anime->public_url) ? $post->public_url : $anime->public_url,
                        ];
                }
            }
            if (!empty($post->casts)) {
                foreach ($post->casts as $cast_name) {
                    $cast = $cast_all->where('name', $cast_name->cast)->first();
                    if (empty($cast)) {
                        $insert_cast_list [] = [
                            'name' => $cast_name->cast,
                        ];
                    }
                }
            }
            if (!empty($post->company)) {
                foreach ($post->company as $company_name) {
                    $company = $company_all->where('name', $company_name)->first();
                    if (empty($company)) {
                        $insert_company_list [] = [
                            'name' => $company_name,
                        ];
                    }
                }
            }
        }
        Anime::upsert($upsert_anime_list, ['id']);

        $insert_cast_list = array_unique($insert_cast_list, SORT_REGULAR);
        Cast::insert($insert_cast_list);

        $insert_company_list = array_unique($insert_company_list, SORT_REGULAR);
        Company::insert($insert_company_list);

        $anime_all = Anime::with(['companies', 'actCasts'])->get();
        $cast_all = Cast::all();
        $company_all = Company::all();
        foreach ($posts as $post) {
            if (!empty($post->casts)) {
                $anime = $anime_all->where('title', $post->title)->first();
                foreach ($post->casts as $cast_name) {
                    $cast = $cast_all->where('name', $cast_name->cast)->first();
                    if (!$anime->actCasts->contains('name', $cast->name)) {
                        $insert_act_cast_list [] = [
                            'anime_id' => $anime->id,
                            'cast_id' => $cast->id,
                        ];
                    }
                }
            }
            if (!empty($post->company)) {
                $anime = $anime_all->where('title', $post->title)->first(); // @phpstan-ignore-line
                foreach ($post->company as $company_name) {
                    $company = $company_all->where('name', $company_name)->first();
                    if (!$anime->companies->contains('name', $company->name)) {
                        $insert_anime_company_list [] = [
                            'anime_id' => $anime->id,
                            'company_id' => $company->id,
                        ];
                    }
                }
            }
        }
        Occupation::insert($insert_act_cast_list, ['id']);
        DB::table('anime_company')->insert($insert_anime_company_list);
    }
}
