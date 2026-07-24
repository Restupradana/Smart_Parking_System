<?php

namespace Database\Seeders;

use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use Illuminate\Database\Seeder;

class ParkingSlotSeeder extends Seeder
{
    public function run(): void
    {
        $areas = ParkingArea::all();

        foreach ($areas as $area) {

            for ($i = 1; $i <= 10; $i++) {

                ParkingSlot::create([
                    'area_id' => $area->id,
                    'slot_number' => $area->name.'-'.$i,
                    'status' => rand(0, 1) ? 'kosong' : 'terisi',
                ]);
            }
        }
    }
}
