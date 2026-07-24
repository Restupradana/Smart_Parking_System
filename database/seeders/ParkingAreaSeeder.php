<?php

namespace Database\Seeders;

use App\Models\ParkingArea;
use Illuminate\Database\Seeder;

class ParkingAreaSeeder extends Seeder
{
    public function run(): void
    {
        if (ParkingArea::count() > 0) {
            return;
        }

        ParkingArea::create([
            'name' => 'Area A',
            'location' => 'Depan Kampus',
        ]);

        ParkingArea::create([
            'name' => 'Area B',
            'location' => 'Belakang Kampus',
        ]);
    }
}
