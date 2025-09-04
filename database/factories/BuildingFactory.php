<?php

namespace Database\Factories;

use App\Models\Neighbourhood;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $numberOfFloors = $this->faker->numberBetween(2, 6);
        $faker = \Faker\Factory::create('ar_SA');
        static $buildingIds;
        if (!$buildingIds) {
            $buildingIds = collect(file(storage_path('app/public/building_ids.txt'), FILE_IGNORE_NEW_LINES))
                ->map(fn($line) => str_replace('way/', '', $line))
                ->shuffle();
        }
        $externalId = $buildingIds->pop();

        return [
            'external_id' => $externalId,
            'name' => 'بناء ' . $faker->unique()->lastName(),
            'is_legal' => $this->faker->boolean(80),
            'number_of_floors' => $numberOfFloors,
            'number_of_floors_violating' => $this->faker->numberBetween(0, 2),
            'type' => $this->faker->randomElement(['مستشفى', 'مدرسة', 'بناء سكني','جامع','كنيسة']),
            'structural_pattern' => $this->faker->randomElement(['إطار بيتوني', 'جدران بيتونية', 'حجري','خشبي','مختلط']),
            'number_of_families_before_departure' => $this->faker->numberBetween(0, $numberOfFloors * 2),
            'number_of_families_after_departure' => $this->faker->numberBetween(0, $numberOfFloors * 2),
            'level_of_damage' => $this->faker->randomElement(['0', '1', '2', '3', '4']),
            'is_materials_from_the_neighborhood' => $this->faker->boolean(60),
            'neighbourhood_id' => Neighbourhood::inRandomOrder()->first()->id,
        ];
    }
}
