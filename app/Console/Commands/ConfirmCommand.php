<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Models\Cast;
use App\Models\UserReview;
use Illuminate\Console\Command;

class ConfirmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'confirm method';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = file_get_contents("data/2022_2_animelist.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);
        $anime_all = Anime::all();
        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (empty($anime)) {
                $anime = new Anime();
                $anime->title = $post->title;
                $anime->title_short = null;
                $anime->year = 2022;
                $anime->coor = 2;
                $anime->public_url = null;
                $anime->twitter = null;
                $anime->hash_tag = null;
                $anime->sex = null;
                $anime->sequel = null;
                $anime->company = $post->company;
                $anime->city_name = null;
            //$anime->save();
            } else {
                $anime->year = 2022;
                $anime->coor = 2;
                $anime->company = $post->company;
                //$anime->save();
            }

            $cast_all = Cast::all();
            foreach ($post->casts as $new_cast) {
                $cast = $cast_all->where('name', $new_cast)->first();
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
                    //$cast->save();
                }
                if (!$cast->isActAnime($anime->id)) {
                    //$cast->actAnimes()->attach($anime->id);
                }
            }
        }
    }
}
