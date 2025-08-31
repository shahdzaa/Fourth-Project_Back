<?php

namespace Database\Seeders;

use App\Models\DamageReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DamageReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DamageReport::factory()->count(20)->create();
    }
}
