<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            VendorSeeder::class,
            UserSeeder::class,
            ExRateSeeder::class,
            CostRateSeeder::class,
            BrandSeeder::class,
            UnitSeeder::class,
            FaceSeeder::class,
            PreDataSeeder::class,
            OrderStatusSeeder::class,

            ]);
    }
}
