<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ModifyCreater>
 */
class ModifyCreaterFactory extends Factory
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
            'creater_id' => 1,
            'furigana' => 'furigana',
            'sex' => 1,
            'url' => 'url',
            'twitter' => 'twitter',
            'blog' => 'blog',
            'blood_type' => 'A',
            'birth' => '2000年4月13日',
            'birthplace' => '東京都',
            'blog_url' => 'blog_url',
        ];
    }
}
