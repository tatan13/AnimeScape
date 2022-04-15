<?php

namespace App\Library;

class DataExceptions
{
    /**
     * ラベルの設定
     * @param Anime $anime
     * @return void
     */
    public function render404IfNotExist($anime)
    {
        if(!$anime->exists()){
            abort(404);
        }
    }
}