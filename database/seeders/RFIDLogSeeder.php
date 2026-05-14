<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RFIDLog;
use App\Models\User;
use App\Models\ParkingSlot;
use Carbon\Carbon;

class RFIDLogSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user yang punya kartu RFID
        $users = User::with('rfidCard')->get();

        // Ambil semua slot beserta relasi area
        $slots = ParkingSlot::with('parkingArea')->get();

        foreach ($users as $user) {

            if (!$user->rfidCard) continue;

            // Simulasi aktivitas 3 hari terakhir
            for ($i = 0; $i < rand(2, 4); $i++) {

                $slot = $slots->random(); // ambil slot acak
                $location = $slot->parkingArea->location ?? 'Unknown Area';

                $masukTime = Carbon::now()
                    ->subDays(rand(0, 2))
                    ->subMinutes(rand(30, 180));

                // MASUK
                RFIDLog::create([
                    'user_id' => $user->id,
                    'rfid_card_id' => $user->rfidCard->id,
                    'slot_id' => $slot->id,
                    'action' => 'masuk',
                    'location' => $location,
                    'logged_at' => $masukTime,
                ]);

                // KELUAR
                RFIDLog::create([
                    'user_id' => $user->id,
                    'rfid_card_id' => $user->rfidCard->id,
                    'slot_id' => $slot->id,
                    'action' => 'keluar',
                    'location' => $location,
                    'logged_at' => $masukTime->copy()->addMinutes(rand(20, 90)),
                ]);
            }
        }
    }
}