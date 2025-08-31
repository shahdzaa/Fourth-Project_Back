<?php

namespace Database\Seeders;

use App\Models\Foundation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoundationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run()
    {
        // Define the sectors you want to insert
        $foundations = [
            ['type' => 'جيز'],
            ['type' => 'بلاطة'],
            ['type' => 'عمود'],
            ['type' => 'جدار'],
            ['type' => 'أساس'],
        ];

        foreach ($foundations as $foundation) {
            DB::table('foundations')->insert([
                'type' => $foundation['type']
            ]);
        }
    }

}
