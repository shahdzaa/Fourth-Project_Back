<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\CommitteeEngineer;
use App\Models\Engineer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CommitteeEngineerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Committee::factory()
            ->count(5)
            ->hasAttached(
                Engineer::factory()->count(3),
                new Sequence(
                    ['is_manager' => 1],
                    ['is_manager' => 0],
                    ['is_manager' => 0],
                ),
                'engineers'
            )
            ->create();
    }
}
