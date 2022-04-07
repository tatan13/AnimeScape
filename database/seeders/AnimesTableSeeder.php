<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use App\Models\Anime;
use Illuminate\Support\Facades\DB;

class AnimesTableSeeder extends Seeder
{
    /**
     * animesテーブルのシーダーを起動
     *
     * @return void
     */
    public function run()
    {
        for ($year = 2022; $year <= 2022; $year++) {
            for ($coor = 2; $coor <= 2; $coor++) {
                $url = "https://api.moemoe.tokyo/anime/v1/master/{$year}/{$coor}";
                $method = "GET";

                $client = new Client();

                $response = $client->request($method, $url);

                $posts = $response->getBody();
                $posts = json_decode($posts);
                $anime_all = Anime::all();
                foreach ($posts as $post) {
                    $anime = $anime_all->where('title', $post->title)->first();
                    if (empty($anime)) {
                        /*
                        $anime = new Anime();
                        $anime->title = $post->title;
                        $anime->title_short = $post->title_short1;
                        $anime->year = $year;
                        $anime->coor = $coor;
                        $anime->public_url = $post->public_url;
                        $anime->twitter = $post->twitter_account;
                        $anime->hash_tag = $post->twitter_hash_tag;
                        $anime->sex = $post->sex;
                        $anime->sequel = $post->sequel;
                        $anime->company = $post->product_companies;
                        $anime->city_name = $post->city_name;
                        $anime->save();
                        */
                    } else {
                        $anime->title_short = $post->title_short1;
                        $anime->year = $year;
                        $anime->coor = $coor;
                        $anime->public_url = $post->public_url;
                        $anime->twitter = $post->twitter_account;
                        $anime->hash_tag = $post->twitter_hash_tag;
                        $anime->sex = $post->sex;
                        $anime->sequel = $post->sequel;
                        $anime->city_name = $post->city_name;
                        $anime->save();
                    }
                }
            }
        }
    }
}
