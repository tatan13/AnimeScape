<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Anime;
use App\Models\UserReview;
use App\Models\AnimeRecommend;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecommendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommend:generate';

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
        AnimeRecommend::query()->delete();
        //すべての点数が付いたユーザーレビューを取得
        $all_user_reviews = UserReview::select(['id', 'anime_id', 'user_id', 'score'])->whereNotNull('score')->get();
        //userのidだけのリスト取得
        $user_id_list = User::pluck('id');
        //最後にSQLに投げる配列を格納するコレクション
        $all_recommend_anime_list = collect();
        //全員分のuserとの類似度を二重ループで計算
        foreach ($user_id_list as $my_id) {
            $recommend_anime_list = collect();
            //類似度とuser_idを格納
            $similarity_list = collect();
            //自分が点数を付けたアニメのidのリストを取得
            $my_review_anime_id_list = UserReview::where('user_id', $my_id)->whereNotNull('score')->pluck('anime_id');
            //類似度を測る相手
            foreach ($user_id_list as $partner_id) {
                if ($my_id !== $partner_id) {
                    //自分と相手のレビューのみを取得
                    $user_reviews_list = $all_user_reviews->whereIn('user_id', [$my_id, $partner_id]);
                    $user_reviews_group_by_anime_id = $user_reviews_list->groupBy('anime_id');
                    $sum = 0;
                    //一つでも点数を付けたアニメが共通していたらtrueになるフラグ
                    $flag = false;
                    foreach ($user_reviews_group_by_anime_id as $user_reviews) {
                        //共通の点数を付けたアニメに関して類似度を計算
                        if ($user_reviews->count() == 2) {
                            $flag = true;
                            $distance = pow($user_reviews[0]->score - $user_reviews[1]->score, 2);
                            $sum += $distance;
                        }
                    }
                    //flagがfalseということは一つも共通の点数を付けたアニメがないということであるので類似度をnullとする
                    $similarity = $flag ? 1 / (1 + sqrt($sum)) : null;
                    $similarity_list->push([
                        'user_id' => $partner_id,
                        'similarity' => $similarity
                    ]);
                }
            }

            //類似度が高い上位5人を取得
            $similarity_list = $similarity_list->whereNotNull('similarity')->sortByDesc('similarity')->take(5);
            foreach ($similarity_list as $similarity) {
                //自分がレビューをつけていなく、相手が高い点数が付けた上位5つのレビューを取得
                $partner_user_reviews =  $all_user_reviews->where('user_id', $similarity['user_id'])
                ->sortByDesc('score')->whereNotIn('anime_id', $my_review_anime_id_list)->take(5);

                //類似度とアニメにつけられた点数の積をおすすめ度とする
                foreach ($partner_user_reviews as $partner_user_review) {
                    $score = ($similarity['similarity'] * $partner_user_review->score);
                    $recommend_anime_list->push([
                        'user_id' => $my_id,
                        'anime_id' => $partner_user_review->anime_id,
                        'recommendation_score' => $score
                    ]);
                }
            }
            $recommend_anime_list = $recommend_anime_list->sortByDesc('score')->unique('anime_id')->take(5);
            // $all_recommend_anime_listに$recommend_anime_listの各要素を追加
            $recommend_anime_list->map(function ($recommend_anime) use ($all_recommend_anime_list) {
                $all_recommend_anime_list->push($recommend_anime);
            });
        }
        DB::table('anime_recommends')->insert($all_recommend_anime_list->toArray());
    }
}
