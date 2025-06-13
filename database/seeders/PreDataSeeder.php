<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pre_data')->insert([
            [
                'year_code' => 25,
                'shohin_gun' => 58,
                'brand_id' => 1,
                'seireki_unit' => 202509,
                'unit_id' => 9,
                'face_code' => 'A',
                'hinban_id' => 157171,
                'kyotu_hinban' => 57171
            ],
            [
                'year_code' => 25,
                'shohin_gun' => 58,
                'brand_id' => 1,
                'seireki_unit' => 202510,
                'unit_id' => 10,
                'face_code' => 'D',
                'hinban_id' => 158471,
                'kyotu_hinban' => 58471
            ],
            [
                'year_code' => 25,
                'shohin_gun' => 58,
                'brand_id' => 7,
                'seireki_unit' => 202510,
                'unit_id' => 10,
                'face_code' => 'C',
                'hinban_id' => 758571,
                'kyotu_hinban' => 758571
            ],
            [

                'year_code' => 25,
                'shohin_gun' => 58,
                'brand_id' => 7,
                'seireki_unit' => 202511,
                'unit_id' => 11,
                'face_code' => 'W',
                'hinban_id' => 759671,
                'kyotu_hinban' => 759671
            ],

        ]);
    }
}
