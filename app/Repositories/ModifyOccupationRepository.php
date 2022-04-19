<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Models\ModifyOccupation;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;

class ModifyOccupationRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return ModifyOccupation::class;
    }

    /**
     *
     */
    public function getModifyOccupationsList()
    {
        return ModifyOccupation::with('anime.occupations')->get()->groupBy('anime_id');
    }

    /**
     *
     */
    public function createModifyOccupation($anime, $cast_name)
    {
        $anime->modifyOccupations()->create(['cast_name' => $cast_name]);
    }
}