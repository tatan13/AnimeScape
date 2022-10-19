<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anime;
use App\Models\UserReview;
use Carbon\Carbon;

class FixAnimeStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix_anime_statistics';

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
        $anime_list = Anime::with("userReviews")->get();
        foreach ($anime_list as $anime) {
            $user_reviews = $anime->userReviews;
            $anime->number_of_interesting_episode = $user_reviews->median('number_of_interesting_episode');
            $anime->median = $user_reviews->median('score');
            $anime->average = $user_reviews->avg('score');
            $anime->max = $user_reviews->max('score');
            $anime->min = $user_reviews->min('score');
            $anime->count = $user_reviews->whereNotNull('score')->count();
            $anime->save();
        }
    }
}
