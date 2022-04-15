<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Http\Requests\SubmitScore;

class AnimeRepository
{
    public function getModelClass()
    {
        return Anime::class;
    }

    /**
     *
     */
    public function getAnimeById($id)
    {
        return $this->Anime::find($id);
    }
}
