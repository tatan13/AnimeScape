<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModifyAnime>
 */
class ModifyAnimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'anime_id' => 1,
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
        ];
    }
}
