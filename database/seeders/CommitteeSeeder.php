<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        foreach ($users as $id) {
            DB::table('committees')->insert([
                'user_id' => $id,
            ]);

        }
    }
}
