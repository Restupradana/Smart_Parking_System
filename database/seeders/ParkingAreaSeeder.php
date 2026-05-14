<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingArea;

class ParkingAreaSeeder extends Seeder
{
    public function run(): void
    {
        ParkingArea::create([
            'name' => 'Area A',
            'location' => 'Depan Kampus'
        ]);

        ParkingArea::create([
            'name' => 'Area B',
            'location' => 'Belakang Kampus'
        ]);
    }
}