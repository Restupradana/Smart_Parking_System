<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Pastikan semua Seeder diimport

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ParkingAreaSeeder::class,
            ParkingSlotSeeder::class,
            SensorSeeder::class,
            RFIDCardSeeder::class,
            RFIDLogSeeder::class,
            ParkingTransactionSeeder::class,
        ]);
    }
}
