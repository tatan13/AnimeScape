<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserReview;
use App\Models\Cast;
use App\Models\Company;
use Carbon\Carbon;

class FixAddTimestampCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix_timestamp';

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
        $company_list = Company::all();
        $upsert_company_list = [];
        foreach ($company_list as $company) {
            $upsert_company_list[] = [
                'id' => $company->id,
                'name' => $company->name,
                'created_at' => new Carbon('2022-05-26 5:30:00'),
            ];
        }
        Company::upsert($upsert_company_list, ['id']);

        $cast_list = Cast::all();
        $upsert_cast_list = [];
        foreach ($cast_list as $cast) {
            $upsert_cast_list[] = [
                'id' => $cast->id,
                'name' => $cast->name,
                'created_at' => new Carbon('2022-05-26 5:30:00'),
            ];
        }
        Cast::upsert($upsert_cast_list, ['id']);

        $user_review_watch_list = UserReview::where('watch', 1)->get();
        $upsert_user_review_watch_list = [];
        foreach ($user_review_watch_list as $user_review) {
            $upsert_user_review_watch_list[] = [
                'id' => $user_review->id,
                'anime_id' => $user_review->anime_id,
                'user_id' => $user_review->user_id,
                'watch_timestamp' => $user_review->updated_at,
            ];
        }
        UserReview::upsert($upsert_user_review_watch_list, ['id']);

        $user_review_comment_list = UserReview::where('one_word_comment', 1)->get();
        $upsert_user_review_comment_list = [];
        foreach ($user_review_comment_list as $user_review) {
            $upsert_user_review_comment_list[] = [
                'id' => $user_review->id,
                'anime_id' => $user_review->anime_id,
                'user_id' => $user_review->user_id,
                'comment_timestamp' => $user_review->updated_at,
            ];
        }
        UserReview::upsert($upsert_user_review_comment_list, ['id']);
    }
}
