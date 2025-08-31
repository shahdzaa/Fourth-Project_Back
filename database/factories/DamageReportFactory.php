<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Committee;
use App\Models\Foundation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DamageReport>
 */
class DamageReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'photo' => $this->faker->imageUrl(500, 500, 'buildings', true, 'damage'),
            'degree_of_damage' => $this->faker->randomElement(['0', '1', '2', '3', '4']),
            'report_number' => $this->faker->unique()->numberBetween(1000, 9999),
            'building_id' => Building::inRandomOrder()->first()->id,
            'foundation_id'=> Foundation::inRandomOrder()->first()->id,
            'committee_id' => Committee::inRandomOrder()->first()->id
        ];
    }
}
