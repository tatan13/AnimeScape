<?php

namespace App\Console\Commands;
use App\Models\Anime;
use App\Models\UserReview;
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
     * @return int
     */
    public function handle()
    {
        $animes = Anime::all();

        foreach($animes as $anime){
            $user_reviews = $anime->user_reviews()->get();
            $anime->median = $user_reviews->median('score');
            $anime->average = $user_reviews->avg('score');
            $anime->max = $user_reviews->max('score');
            $anime->min = $user_reviews->min('score');
            $anime->count = $user_reviews->count();
            $anime->save();
        }
    }
}
