<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Models\Company;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class AddCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add_company method';

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
        $posts = file_get_contents("data/2022_05_21_production_anime.json");
        $posts = mb_convert_encoding($posts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $posts = json_decode($posts);

        $company_all = Company::all();
        $insert_company_list = [];
        $insert_anime_company_list = [];
        foreach ($posts as $post) {
            if (empty($post->company)) {
                continue;
            }
            $company = $company_all->where('name', $post->company)->first();
            if (empty($company)) {
                $insert_company_list [] = [
                    'name' => $post->company,
                ];
            }
        }

        $insert_company_list = array_unique($insert_company_list, SORT_REGULAR);
        Company::insert($insert_company_list);

        $anime_all = Anime::with('companies')->get();
        $company_all = Company::all();
        foreach ($posts as $post) {
            if (empty($post->company)) {
                continue;
            }
            $anime = $anime_all->where('title', $post->title)->first(); // @phpstan-ignore-line
            if (empty($anime)) {
                continue;
            }
            $company = $company_all->where('name', $post->company)->first();
            if (!$anime->companies->contains('name', $company->name)) {
                $insert_anime_company_list [] = [
                    'anime_id' => $anime->id,
                    'company_id' => $company->id,
                ];
            }
        }
        DB::table('anime_company')->insert($insert_anime_company_list);
    }
}
