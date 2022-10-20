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
            $anime->max = $user_reviews->max('score'); // @phpstan-ignore-line
            $anime->min = $user_reviews->min('score'); // @phpstan-ignore-line
            $anime->count = $user_reviews->whereNotNull('score')->count();
            $anime->stdev = self::standardDeviation($user_reviews->whereNotNull('score')->pluck('score')->toArray());
            $anime->save();
        }
    }

    /**
     * 平均を求める
     *
     * @return float | null
     */
    public static function average(array $values)
    {
        $count = count($values);

        if ($count == 0) {
            return null;
        }
        return (float) (array_sum($values) / count($values));
    }

    /**
     * 分散を求める
     *
     * @return float | null
     */
    public static function variance(array $values)
    {
        $count = count($values);

        if ($count == 0) {
            return null;
        }

        // 平均値を求める
        $ave = self::average($values);

        $variance = 0.0;
        foreach ($values as $val) {
            $variance += pow($val - $ave, 2);
        }
        return (float) ($variance / count($values));
    }

    /**
     * 標準偏差を求める
     *
     * @return float | null
     */
    public static function standardDeviation(array $values)
    {
        $variance = self::variance($values);
        if (is_null($variance)) {
            return null;
        }
        return (float) sqrt($variance);
    }
}
