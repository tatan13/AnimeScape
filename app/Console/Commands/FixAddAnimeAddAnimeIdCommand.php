<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AddAnime;
use App\Models\Anime;
use Carbon\Carbon;

class FixAddAnimeAddAnimeIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix_add_anime_add_anime_id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $add_anime_list = AddAnime::all();
        $anime_list = Anime::all();
        $upsert_add_anime_list = [];
        foreach ($add_anime_list as $add_anime) {
            $anime = $anime_list->where('title', $add_anime->title)->first();
            $upsert_add_anime_list[] = [
                'id' => $add_anime->id,
                'title' => $add_anime->title,
                'anime_id' => $anime->id,
                'updated_at' => $add_anime->updated_at
            ];
        }
        AddAnime::upsert($upsert_add_anime_list, ['id']);
    }
}
