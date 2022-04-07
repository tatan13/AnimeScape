<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Occupation;

class CastsTableSeeder extends Seeder
{
    /**
     * castsテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        for ($year = 2021; $year <= 2022; $year++) {
            for ($coor = 1; $coor <= 4; $coor++) {
                /*
                $url = "http://127.0.0.1:80/data/{$year}_{$coor}_cast.json";
                $method = "GET";

                $client =new Client();

                $response = $client->request($method, $url);

                $posts = $response->getBody();
                */
                $posts = file_get_contents("data/{$year}_{$coor}_cast.json");
                $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                $posts = json_decode($posts);

                foreach ($posts as $post) {
                    $anime = Anime::where('title', $post->title)->first();

                    if (empty($anime)) {
                        $anime = new Anime();
                        $anime->title = $post->title;
                        $anime->title_short = null;
                        $anime->year = $year;
                        $anime->coor = $coor;
                        $anime->public_url = null;
                        $anime->twitter = null;
                        $anime->hash_tag = null;
                        $anime->sex = null;
                        $anime->sequel = null;
                        $anime->company = null;
                        $anime->city_name = null;
                        $anime->created_at = Carbon::now();
                        $anime->updated_at = Carbon::now();
                        $anime->save();
                    }

                    foreach ($post->casts as $new_cast) {
                        $cast = Cast::where('name', $new_cast)->first();
                        if (empty($cast)) {
                            $cast = new Cast();
                            $cast->name = $new_cast;
                            $cast->furigana = null;
                            $cast->sex = null;
                            $cast->office = null;
                            $cast->url = null;
                            $cast->twitter = null;
                            $cast->twitter = null;
                            $cast->blog = null;
                            $cast->created_at = Carbon::now();
                            $cast->updated_at = Carbon::now();
                            $cast->save();
                        }

                        $occupation = new Occupation();
                        $occupation->cast_id = $cast->id;
                        $occupation->created_at = Carbon::now();
                        $occupation->updated_at = Carbon::now();
                        $anime->occupations()->save($occupation);
                    }
                }

                if ($year == 2022 && $coor == 1) {
                    break;
                }
            }
        }
    }
}
