<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

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
        $file = public_path('/sitemap.xml');
        $start_content = '<?xml version="1.0" encoding="UTF-8"?>'
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        \File::put($file, $start_content);

        $content = "<url>
            <loc>" . route('index.show') . "</loc>
            <lastmod>2022-05-27</lastmod>
            </url>";
        \File::append($file, $content);

        $anime_list = \App\Models\Anime::select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($anime_list as $anime) {
            $content = "<url>
            <loc>" . route('anime.show', ['anime_id' => $anime->id]) . "</loc>
            <lastmod>" . $anime->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $user_review_list = \App\Models\UserReview::whereNotNull('long_word_comment')
        ->select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($user_review_list as $user_review) {
            $content = "<url>
            <loc>" . route('user_anime_comment.show', ['user_review_id' => $user_review->id]) . "</loc>
            <lastmod>" . $user_review->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $cast_list = \App\Models\Cast::select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($cast_list as $cast) {
            $content = "<url>
            <loc>" . route('cast.show', ['cast_id' => $cast->id]) . "</loc>
            <lastmod>" . $cast->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $content = "<url>
            <loc>" . route('cast_list.show') . "</loc>
            <lastmod>2022-05-10</lastmod>
            </url>";
        \File::append($file, $content);

        $creater_list = \App\Models\Creater::select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($creater_list as $creater) {
            $content = "<url>
            <loc>" . route('creater.show', ['creater_id' => $creater->id]) . "</loc>
            <lastmod>" . $creater->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $content = "<url>
            <loc>" . route('creater_list.show') . "</loc>
            <lastmod>2022-05-10</lastmod>
            </url>";
        \File::append($file, $content);

        $company_list = \App\Models\Company::select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($company_list as $company) {
            $content = "<url>
            <loc>" . route('company.show', ['company_id' => $company->id]) . "</loc>
            <lastmod>" . $company->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $tag_list = \App\Models\Tag::select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($tag_list as $tag) {
            $content = "<url>
            <loc>" . route('tag.show', ['tag_id' => $tag->id]) . "</loc>
            <lastmod>" . $tag->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $content = "<url>
        <loc>" . route('tag_list.show') . "</loc>
        <lastmod>2022-12-7</lastmod>
        </url>";
        \File::append($file, $content);

        $user_list = \App\Models\User::select(['id', 'updated_at'])->oldest('id')->get();
        foreach ($user_list as $user) {
            $content = "<url>
            <loc>" . route('user.show', ['user_id' => $user->id]) . "</loc>
            <lastmod>" . $user->updated_at->format("Y-m-d") . "</lastmod>
            </url>";
            \File::append($file, $content);
        }

        $content = "<url>
            <loc>" . route('contact.show') . "</loc>
            <lastmod>" . \App\Models\Contact::latest()->first()->created_at->format("Y-m-d") . "</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('anime_statistics.show') . "</loc>
            <lastmod>2022-05-27</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('add_anime_log.show') . "</loc>
            <lastmod>"
            . \App\Models\AddAnime::where('delete_flag', 1)->latest()->first()->updated_at->format("Y-m-d") .
            "</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('add_cast_log.show') . "</loc>
            <lastmod>"
            . \App\Models\AddCast::where('delete_flag', 1)->latest()->first()->updated_at->format("Y-m-d") .
            "</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('add_creater_log.show') . "</loc>
            <lastmod>"
            . \App\Models\AddCreater::where('delete_flag', 1)->latest()->first()->updated_at->format("Y-m-d") .
            "</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('update_log.show') . "</loc>
            <lastmod>2022-05-27</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('site_information.show') . "</loc>
            <lastmod>2022-05-12</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('privacy_policy.show') . "</loc>
            <lastmod>2022-05-10</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('register') . "</loc>
            <lastmod>2022-05-10</lastmod>
            </url>";
        \File::append($file, $content);

        $content = "<url>
            <loc>" . route('login') . "</loc>
            <lastmod>2022-05-10</lastmod>
            </url>";
        \File::append($file, $content);

        $end_content = '</urlset>';
        \File::append($file, $end_content);
    }
}
