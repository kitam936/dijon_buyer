<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([[
            'id' => 1,
            'role_name' => '管理者',
            'role_info' => '管理者権限',
        ],
        [
            'id' => 2,
            'role_name' => '管理者代行',
            'role_info' => '管理者権限',
        ],
        [
            'id' => 3,
            'role_name' => 'チーフバイヤー',
            'role_info' => 'チーフバイヤー権限',
        ],
        [
            'id' => 5,
            'role_name' => 'バイヤー',
            'role_info' => 'バイヤー権限',
        ],
        [
            'id' => 9,
            'role_name' => 'スタッフメンバー',
            'role_info' => 'スタッフメンバー',
        ],

    ]);
    }
}
