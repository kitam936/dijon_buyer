<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ex_rates')->insert([[
            'id' => 1,
            'ex_rate' => 100,
            'ex_memo' => 'YEN',
        ],
    ]);
    }
}
