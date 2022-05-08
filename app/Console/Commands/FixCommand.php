<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Models\UserReview;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fix method';

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
     * @return void
     */
    public function handle()
    {
        $posts = file_get_contents("data/anime_cast_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $anime_all = Anime::all();
        foreach ($posts as $post) {
            if (
                $post->year != 2022 &&
                $post->year != 2021 &&
                $post->year != 2020
            ) {
                continue;
            }
            $anime = $anime_all->where('title', $post->title)->first();
            if (empty($anime)) {
                echo $post->title . "\n";
            }
        }
        // $animes = Anime::all();
        // foreach ($animes as $anime) {
        //     $user_reviews = $anime->userReviews()->get();
        //     $anime->median = $user_reviews->median('score');
        //     $anime->average = $user_reviews->avg('score');
        //     $anime->max = $user_reviews->max('score');
        //     $anime->min = $user_reviews->min('score');
        //     $anime->count = $user_reviews->count();
        //     $anime->save();
        // }
        // $url = "https://api.moemoe.tokyo/anime/v1/master/2022/2";
        // $method = "GET";

        // $client = new Client();

        // $response = $client->request($method, $url);

        // $posts = $response->getBody();
        // $posts = json_decode($posts);
        // $all_anime_list = Anime::all();

        // foreach ($posts as $post) {
        //     $anime = $all_anime_list->where('title', $post->title)->first();
        //     if (empty($anime)) {
        //         print_r($post);
        //     }
        // }
    }
}
