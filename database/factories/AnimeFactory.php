<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anime>
 */
class AnimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'title_short' => $this->faker->name(),
            'year' => 2022,
            'coor' => 1,
            'public_url' => 'https://public_url',
            'twitter' => 'twitterId',
            'hash_tag' => 'hashTag',
            'sex' => 1,
            'sequel' => 1,
            'company' => 'company',
            'city_name' => 'city_name',
            'average' => 100,
            'median' => 100,
            'max' => 100,
            'min' => 100,
            'count' => 1,
        ];
    }
}
