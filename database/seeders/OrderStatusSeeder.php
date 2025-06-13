<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([[
            'id' => 1,
            'status_name' => 'New',
            'status_info' => '新規発注',
        ],
        [
            'id' => 5,
            'status_name' => '本部DL済',
            'status_info' => '本部DL済',
        ],
        [
            'id' => 7,
            'status_name' => '発注済',
            'status_info' => '発注済',
        ],
        [
            'id' => 9,
            'status_name' => 'マスター登録済',
            'status_info' => 'マスター登録済',
        ],


    ]);
    }
}
