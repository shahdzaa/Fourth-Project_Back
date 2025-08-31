<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingsGeojsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $geojsonPath = base_path('buildings.geojson');
        $data = json_decode(file_get_contents($geojsonPath), true);
        if (!isset($data['features'])) {
            return;
        }
        foreach ($data['features'] as $feature) {
            $props = $feature['properties'] ?? [];
            DB::table('buildings')->insert([
                'name' => $props['name'] ?? null,
                'building' => $props['building'] ?? null,
                'damage' => null,
                'legal' => 0,
                'numberOfFloors' => 0,
                'numberOfFloorsViolating' => 0,
                'structuralPattern' => '',
                'numberOfFamiliesLiving' => 0,
                'levelOfDamage' => 0,
                'neighbourhood_id' => 1, // Make sure neighbourhood with id=1 exists
            ]);
        }
    }
}
