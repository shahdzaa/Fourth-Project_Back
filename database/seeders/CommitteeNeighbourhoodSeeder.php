<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\CommitteeNeighbourhood;
use App\Models\Neighbourhood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommitteeNeighbourhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run()
    {
        $committees = Committee::all();
        $neighbourhoods = Neighbourhood::all();

        foreach ($committees as $committee) {
            // ربط اللجنة مع 2-3 أحياء عشوائية
            $randomNeighbourhoods = $neighbourhoods->random(rand(2, 3))->pluck('id')->toArray();
            $committee->neighbourhoods()->attach($randomNeighbourhoods);
        }
    }
}
