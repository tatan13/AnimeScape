<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Models\ModifyAnime;
use App\Http\Requests\ModifyAnimeRequest;
use Illuminate\Support\Facades\Auth;

class ModifyAnimeRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return ModifyAnime::class;
    }

    /**
     *
     */
    public function createModifyAnime($anime, ModifyAnimeRequest $request)
    {
        $anime->modifyAnimes()->create($request->validated());
    }

    /**
     *
     */
    public function getAnime(ModifyAnime $modify_anime)
    {
        return $modify_anime->anime;
    }

    /**
     *
     */
    public function delete(ModifyAnime $modify_anime)
    {
        $modify_anime->delete();
    }
}