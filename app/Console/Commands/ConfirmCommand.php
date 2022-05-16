<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Models\UserReview;
use GuzzleHttp\Client;
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
     * @return void
     */
    public function handle()
    {
        $posts = file_get_contents("data/AH_anime_list.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $anime_all = Anime::all();
        foreach ($posts as $post) {
            $anime = $anime_all->where('title', $post->title)->first();
            if (!empty($anime)) {
                echo $post->title . "\n";
            }
        }
    }
}
