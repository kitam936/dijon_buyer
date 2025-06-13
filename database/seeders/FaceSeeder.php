<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faces')->insert([
            [
            'id' => 1,
            'face_code' => 'A',
            'face_item' => 'SH',
            ],
            [
            'id' => 2,
            'face_code' => 'D',
            'face_item' => 'CS',
            ],
            [
            'id' => 3,
            'face_code' => 'N',
            'face_item' => 'CS',
            ],
            [
            'id' => 4,
            'face_code' => 'E',
            'face_item' => 'KN',
            ],
            [
            'id' => 5,
            'face_code' => 'C',
            'face_item' => 'CS',
            ],
            [
            'id' => 6,
            'face_code' => 'W',
            'face_item' => 'JK',
            ],
            [
            'id' => 7,
            'face_code' => 'B',
            'face_item' => 'BT',
            ],
            [
            'id' => 9,
            'face_code' => 'F',
            'face_item' => 'komono',
            ],

        ]);
    }
}
