<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('users')->insert([
        [
            'id' => 1,
            'name' => '北村',
            'email' => 'kitamura@dijon.co.jp',
            'password' => Hash::make('tk9521dj'),
            'role_id' => 1,
            'shop_id' => 101,
            'mailService' => 1
        ],
        [
            'id' => 2,
            'name' => '村山',
            'email' => 'murayama@dijon.co.jp',
            'password' => Hash::make('im4278dj'),
            'role_id' => 3,
            'shop_id' => 101,
            'mailService' => 1
        ],
        [
            'id' => 9,
            'name' => '古川',
            'email' => 'furukawa@dijon.co.jp',
            'password' => Hash::make('mf3847dj'),
            'role_id' => 7,
            'shop_id' => 101,
            'mailService' => 1
        ],


    ]);
    }
}
