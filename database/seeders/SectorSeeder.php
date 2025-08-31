<?php

namespace Database\Seeders;

use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the sectors you want to insert
        $sectors = [
            ['name' => 'قاضي عسكر'],
            ['name' => 'سريان'],
            ['name' => 'الانصاري'],
            ['name' => 'حلب الجديدة'],
        ];

        // Insert the data into the 'sectors' table
        foreach ($sectors as $sector) {
            DB::table('sectors')->insert([
                'name' => $sector['name'],
            ]);
        }
    }
}
