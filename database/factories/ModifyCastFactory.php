<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModifyCast>
 */
class ModifyCastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'cast_id' => 1,
            'furigana' => 'furigana',
            'sex' => 1,
            'office' => 'office',
            'url' => 'url',
            'twitter' => 'twitter',
            'blog' => 'blog',
        ];
    }
}