<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Company;
use App\Models\Occupation;
use Illuminate\Support\Facades\DB;

class AhCompaniesTableSeeder extends Seeder
{
    /**
     * animesテーブル，castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/AH_anime_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $company_all = Company::all();
        $insert_company_list = [];
        $insert_anime_company_list = [];

        foreach ($posts as $post) {
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
        $insert_company_list = array_unique($insert_company_list, SORT_REGULAR);
        Company::insert($insert_company_list);

        $anime_all = Anime::with('companies')->get();
        $company_all = Company::all();
        foreach ($posts as $post) {
            if (!empty($post->company)) {
                $anime = $anime_all->where('title', $post->title)->first(); // @phpstan-ignore-line
                if (empty($anime)) {
                    continue;
                }
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
        DB::table('anime_company')->insert($insert_anime_company_list);
    }
}
