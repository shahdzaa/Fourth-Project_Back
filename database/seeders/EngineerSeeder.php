<?php

namespace Database\Seeders;

use App\Models\Engineer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EngineerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Engineer::factory(50)->create();
    }
}
