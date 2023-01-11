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
        $checked = array();
        for ($i = 0; $i < count($occupation_all); $i++) {
            for ($j = $i + 1; $j < count($occupation_all); $j++) {
                if (in_array($j, $checked)) {
                    continue;
                }
                if (
                    ($occupation_all[$i]->anime_id == $occupation_all[$j]->anime_id) &&
                    ($occupation_all[$i]->cast_id == $occupation_all[$j]->cast_id)
                ) {
                    $occupation_all[$i]->character =
                    $occupation_all[$i]->character . '、' . $occupation_all[$j]->character;
                    $occupation_all[$i]->save();
                    $occupation_all[$j]->delete();
                    array_push($checked, $j);
                }
            }
        }
    }
}
