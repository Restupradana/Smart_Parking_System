<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Pastikan semua Seeder diimport
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ParkingAreaSeeder;
use Database\Seeders\ParkingSlotSeeder;
use Database\Seeders\SensorSeeder;
use Database\Seeders\RFIDCardSeeder;
use Database\Seeders\RFIDLogSeeder;
use Database\Seeders\ParkingTransactionSeeder;

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