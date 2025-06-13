<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            [
                'id' => 1,
                'brand_name' => 'TBIS',
                'brand_info' => 'TBIS',
                'kizoku_g' => 110,
            ],
            [
                'id' => 7,
                'brand_name' => 'notorico',
                'brand_info' => 'notorico',
                'kizoku_g' => 710,
            ],
        ]);
    }
}
