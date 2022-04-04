<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anime;
use App\Models\Cast;
use Illuminate\Support\Facades\DB;

class AnimesCastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = file_get_contents("data/2022_2_animelist.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);
        $anime_all = Anime::all();
        foreach($posts as $post){
            $anime = $anime_all->where('title', $post->title)->first();
            if(empty($anime)){
                $anime = new Anime();
                $anime->title = $post->title;
                $anime->title_short = NULL;
                $anime->year = 2022;
                $anime->coor = 2;
                $anime->public_url = NULL;
                $anime->twitter = NULL;
                $anime->hash_tag = NULL;
                $anime->sex = NULL;
                $anime->sequel = NULL;
                $anime->company = $post->company;
                $anime->city_name = NULL;
                $anime->save();
            }else{
                $anime->year = 2022;
                $anime->coor = 2;
                $anime->company = $post->company;
                $anime->save();
            }

            $cast_all = Cast::all();
            foreach($post->casts as $new_cast){
                $cast = $cast_all->where('name', $new_cast)->first();
                if(empty($cast)){
                    $cast = new Cast();
                    $cast->name = $new_cast;
                    $cast->furigana = NULL;
                    $cast->sex = NULL;
                    $cast->office = NULL;
                    $cast->url = NULL;
                    $cast->twitter = NULL;
                    $cast->twitter = NULL;
                    $cast->blog = NULL;
                    $cast->save();
                }
                if(!$cast->isActAnime($anime->id)){
                    $cast->actAnimes()->attach($anime->id);
                }
            }
        }
    }
}
