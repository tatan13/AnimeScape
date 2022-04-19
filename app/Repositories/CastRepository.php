<?php

namespace App\Repositories;

use App\Models\Cast;
use App\Models\Anime;

class CastRepository extends AbstractRepository
{
    public function getModelClass(): string
    {
        return Cast::class;
    }

    /**
     *
     */
    public function getActAnimes(Cast $cast)
    {
        return $cast->actAnimes;
    }

    /**
     *
     */
    public function update(Cast $cast)
    {
        $cast->save();
    }

    /**
     *
     */
    public function getCastByName($cast_name)
    {
        return Cast::where('name', $cast_name)->first();
    }

    /**
     *
     */
    public function createOccupation(Cast $cast, Anime $anime)
    {
        $cast->actAnimes()->attach($anime->id);
    }
}