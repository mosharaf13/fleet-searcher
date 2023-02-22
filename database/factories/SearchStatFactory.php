<?php

namespace Database\Factories;

use App\Models\SearchStat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchStat>
 */
class SearchStatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchStat::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ads_count' => fake()->numberBetween(0, 10),
            'keyword' => fake()->sentence,
            'links_count' => fake()->numberBetween(0, 10),
            'total_result_count' => fake()->numberBetween(0, 10),
            'raw_response' => fake()->randomHtml,
            'user_id' => 1
        ];
    }
}
