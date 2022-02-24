<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class AnimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for($year=2021;$year<=2022;$year++){
            for($coor=1;$coor<=4;$coor++){
                $url = "https://api.moemoe.tokyo/anime/v1/master/{$year}/{$coor}";
                $method = "GET";
        
                $client =new Client();
        
                $response = $client->request($method, $url);
        
                $posts = $response->getBody();
                $posts = json_decode($posts);
        
                foreach($posts as $post){
                    DB::table('animes')->insert([
                        'title' => $post->title,
                        'title_short' => $post->title_short1,
                        'year' => $year,
                        'coor' => $coor,
                        'public_url' => $post->public_url,
                        'twitter' => $post->twitter_account,
                        'hash_tag' => $post->twitter_hash_tag,
                        'sex' => $post->sex,
                        'sequel' => $post->sequel,
                        'company' => $post->product_companies,
                        'city_name' => $post->city_name,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
                if($year==2022&&$coor==1){
                    break;
                }
                sleep(5);
            }
        }    
        
        /*
        $url = "https://api.moemoe.tokyo/anime/v1/master/2021/4";
                $method = "GET";
        
                $client =new Client();
        
                $response = $client->request($method, $url);
        
                $posts = $response->getBody();
                $posts = json_decode($posts);
        
                foreach($posts as $post){
                    DB::table('animes')->insert([
                        'title' => $post->title,
                        'title_short' => $post->title_short1,
                        'year' => 2021,
                        'coor' => 4,
                        'public_url' => $post->public_url,
                        'twitter' => $post->twitter_account,
                        'hash_tag' => $post->twitter_hash_tag,
                        'sex' => $post->sex,
                        'sequel' => $post->sequel,
                        'company' => $post->product_companies,
                        'city_name' => $post->city_name,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                */
    }
}
