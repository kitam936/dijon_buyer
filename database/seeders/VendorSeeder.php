<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendors')->insert([
        [
            'id' => 8419,
            'vendor_name' => '衆力',
            'vendor_info' => '衆力',

        ],
        [
            'id' => 8500,
            'vendor_name' => 'フジスター',
            'vendor_info' => 'フジスター',

        ],
    ]);
    }
}
