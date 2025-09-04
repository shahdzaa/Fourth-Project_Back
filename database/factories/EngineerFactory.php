<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Engineer>
 */
class EngineerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ar_SA');
        $syrianPhoneNumber = $this->faker->unique()->regexify('\+9639[345689][0-9]{7}');
        return [
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'phone_number' => $syrianPhoneNumber,
            'address' => $faker->address(),
            'age' => $this->faker->dateTimeBetween('-60 years', '-24 years')->format('Y-m-d'),
            'specialization' => $this->faker->randomElement(['مدني', 'عمارة', 'كهرباء', 'ميكانيك']),
        ];

    }
}
