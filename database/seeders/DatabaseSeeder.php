<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        User::factory(50)->create();
        $this->call([
//            SectorSeeder::class,
//            NeighbourhoodSeeder::class,
//            BuildingSeeder::class,
//            FoundationSeeder::class,
//            CommitteeSeeder::class,
//            DamageReportSeeder::class,
//            CommitteeNeighbourhoodSeeder::class,
//            EngineerSeeder::class,
//            CommitteeEngineerSeeder::class,
        ]);
         User::factory()->create([
             'name' => 'Admin',
             'email' => 'admin@example.com',
             'password'=>'123456789+'
         ]);
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password'=>'123456789-'
        ]);
        //$this->call(BuildingsGeojsonSeeder::class);
    }
}
