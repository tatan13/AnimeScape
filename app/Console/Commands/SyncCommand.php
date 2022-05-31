<?php

namespace App\Console\Commands;

use App\Models\Anime;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync method';

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
        $url = "https://www.animescape.link/api/anime";
        $method = "GET";

        $client = new Client();

        $response = $client->request($method, $url);
        $insert_anime_list = [];
        $posts = $response->getBody();
        $posts = json_decode($posts);
        foreach ($posts as $post) {
            $insert_anime_list [] = [
                'id' => null,
                'title' => $post->title,
                'year' => $post->year,
                'coor' => $post->coor,
            ];
        }
        Anime::upsert($insert_anime_list, ['id']);
    }
}
