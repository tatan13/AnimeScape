<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Anime;
use App\Models\UserReview;
use Illuminate\Console\Command;

class RecommendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recommend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'recommend method';

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
        $all_user_reviews = UserReview::select(['id', 'anime_id', 'user_id', 'score'])->whereNotNull('score')->get();
        $user_id_list = User::pluck('id');
        foreach ($user_id_list as $my_id) {
            $recommend_anime_list = collect(); //きえる
            $similarity_list = collect();
            $my_review_anime_id_list = UserReview::where('user_id', $my_id)->whereNotNull('score')->pluck('anime_id');
            foreach ($user_id_list as $partner_id) {
                if ($my_id !== $partner_id) {
                    $user_reviews_list = $all_user_reviews->whereIn('user_id', [$my_id, $partner_id]);
                    $user_reviews_group_by_anime_id = $user_reviews_list->groupBy('anime_id');
                    $sum = 0;
                    $flag = false;
                    foreach ($user_reviews_group_by_anime_id as $user_reviews) {
                        if ($user_reviews->count() == 2) {
                            $flag = true;
                            $distance = pow($user_reviews[0]->score - $user_reviews[1]->score, 2);
                            $sum += $distance;
                        }
                    }
                    $similarity = $flag ? 1 / (1 + sqrt($sum)) : null;
                    echo $my_id . " " . $partner_id . " " . $similarity . "\n";
                    $similarity_list->push([
                        'user_id' => $partner_id,
                        'similarity' => $similarity
                    ]);
                }
            }

            $similarity_list = $similarity_list->whereNotNull('similarity')->sortByDesc('similarity')->take(5);
            foreach ($similarity_list as $similarity) {
                $partner_user_reviews =  $all_user_reviews->where('user_id', $similarity['user_id'])
                ->sortByDesc('score')->whereNotIn('anime_id', $my_review_anime_id_list)->take(5);
                echo $my_review_anime_id_list . "\n";
                echo $partner_user_reviews . "\n";
                foreach ($partner_user_reviews as $partner_user_review) {
                    $score = ($similarity['similarity'] * $partner_user_review->score);
                    echo $my_id . " " . $similarity['user_id'] . " " . $similarity['similarity']
                    . " " . $partner_user_review->score
                    . " " . ($similarity['similarity'] * $partner_user_review->score) . "\n";
                    $anime = Anime::find($partner_user_review->anime_id);
                    $recommend_anime_list->push(['anime' => $anime, 'score' => $score]);
                }
            }
            $recommend_anime_list = $recommend_anime_list->sortByDesc('score')->unique('anime')->take(5);
            foreach ($recommend_anime_list as $recommend_anime) {
                echo "your recommended anime is " . $recommend_anime['anime']->title;
                echo "\n";
            }
        }
    }
}
