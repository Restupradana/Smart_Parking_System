<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingSlot;
use App\Models\ParkingArea;

class ParkingSlotSeeder extends Seeder
{
    public function run(): void
    {
        $areas = ParkingArea::all();

        foreach ($areas as $area) {

            for ($i = 1; $i <= 10; $i++) {

                ParkingSlot::create([
                    'area_id' => $area->id,
                    'slot_number' => $area->name . '-' . $i,
                    'status' => rand(0, 1) ? 'kosong' : 'terisi',
                ]);
            }
        }
    }
}