<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cost_rates')->insert([[
            'id' => 1,
            'cost_rate' => 160,
            'cost_memo' => '手数料等込み',
        ],
    ]);
    }
}
