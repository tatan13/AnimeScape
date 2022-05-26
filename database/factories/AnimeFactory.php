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
            'furigana' => 'anime_furigana',
            'year' => 2022,
            'coor' => 1,
            'number_of_episode' => 12,
            'public_url' => 'https://public_url',
            'twitter' => 'twitterId',
            'hash_tag' => 'hashTag',
            'city_name' => 'city_name',
            'summary' => 'summary',
            'd_anime_store_id' => 'd_anime_store_id',
            'amazon_prime_video_id' => 'amazon_prime_video_id',
            'unext_id' => 'unext_id',
            'fod_id' => 'fod_id',
            'abema_id' => 'abema_id',
            'disney_plus_id' => 'disney_plus_id',
            'number_of_interesting_episode' => 12,
            'average' => 100,
            'median' => 100,
            'max' => 100,
            'min' => 100,
            'count' => 1,
        ];
    }
}
