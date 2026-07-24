<?php

namespace Database\Seeders;

use App\Models\ParkingSlot;
use App\Models\Sensor;
use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    public function run(): void
    {
        $slots = ParkingSlot::all();

        foreach ($slots as $slot) {

            Sensor::create([
                'slot_id' => $slot->id,
                'type' => 'ultrasonic',
                'last_status' => $slot->status,
            ]);
        }
    }
}
