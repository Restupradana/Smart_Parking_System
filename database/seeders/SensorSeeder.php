<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;
use App\Models\ParkingSlot;

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