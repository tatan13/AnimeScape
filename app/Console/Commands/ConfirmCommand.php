<?php

namespace App\Console\Commands;

use App\Models\Occupation;
use App\Models\AnimeCreater;
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
        $occupation_all = Occupation::with(['cast'])->get();
        foreach ($occupation_all as $occupation) {
            if ($occupation->character == "") {
                $occupation->character = null;
                $occupation->save();
            }
        }
        $anime_creater_all = AnimeCreater::with(['creater'])->get();
        foreach ($anime_creater_all as $anime_creater) {
            if ($anime_creater->occupation == "") {
                $anime_creater->occupation = null;
                $anime_creater->save();
            }
        }
    }
}
