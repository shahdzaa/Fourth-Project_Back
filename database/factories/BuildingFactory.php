<?php

namespace Database\Factories;

use App\Models\Building; // تأكد من استدعاء الموديل
use App\Models\Neighbourhood;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Building::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ar_SA');
        $numberOfFloors = $this->faker->numberBetween(2, 6);

        // هذه هي البيانات التي سيتم إنشاؤها
        return [
            'external_id' => $this->faker->unique()->randomNumber(), // سنستخدم قيمة وهمية مؤقتة
            'name' => 'بناء ' . $faker->lastName(),
            'is_legal' => $this->faker->boolean(80),
            'number_of_floors' => $numberOfFloors,
            'number_of_floors_violating' => $this->faker->numberBetween(0, 2),
            'type' => $this->faker->randomElement(['مستشفى', 'مدرسة', 'بناء سكني', 'جامع', 'كنيسة']),
            'structural_pattern' => $this->faker->randomElement(['إطار بيتوني', 'جدران بيتونية', 'حجري', 'خشبي', 'مختلط']),
            'number_of_families_before_departure' => $this->faker->numberBetween(0, $numberOfFloors * 2),
            'number_of_families_after_departure' => $this->faker->numberBetween(0, $numberOfFloors * 2),
            'level_of_damage' => $this->faker->randomElement(['0', '1', '2', '3', '4']),
            'is_materials_from_the_neighborhood' => $this->faker->boolean(60),
            'neighbourhood_id' => Neighbourhood::inRandomOrder()->first()->id,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        // قائمة الأرقام الفريدة
        static $buildingIds;

        if (!$buildingIds) {
            $path = storage_path('app/public/building_ids.txt');
            if (!file_exists($path)) {
                throw new \Exception("Building IDs file not found at: {$path}");
            }
            $buildingIds = collect(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
                ->map(fn($line) => str_replace('way/', '', $line))
                ->unique()
                ->shuffle();
        }

        return $this->afterCreating(function (Building $building) use (&$buildingIds) {
            if ($buildingIds->isNotEmpty()) {
                // ابحث عن رقم غير مستخدم في قاعدة البيانات
                do {
                    $externalId = $buildingIds->pop();
                } while (is_null($externalId) || Building::where('external_id', $externalId)->exists());

                // إذا وجدنا رقماً صالحاً، قم بتحديث السجل الذي تم إنشاؤه للتو
                if (!is_null($externalId)) {
                    $building->external_id = $externalId;
                    $building->save();
                }
            }
        });
    }
}
