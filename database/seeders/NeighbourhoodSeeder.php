<?php

namespace Database\Seeders;

use App\Models\Neighbourhood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NeighbourhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the sectors you want to insert
        $neighbourhoods = [
            ['name' => 'الحمدانية'],
            ['name' => 'صلاح الدين'],
            ['name' => 'الزهراء'],
            ['name' => 'الأعظمية'],
        ];

        // Insert the data into the 'sectors' table
        foreach ($neighbourhoods as $neighbourhood) {
            DB::table('neighbourhoods')->insert([
                'name' => $neighbourhood['name'],
                'sector_id'=>4
            ]);
        }
    }
}
