<?php

namespace Database\Seeders;

use App\Models\ParkingSlot;
use App\Models\ParkingTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParkingTransactionSeeder extends Seeder
{
    public function run(): void
    {
        if (ParkingTransaction::count() > 0) {
            return;
        }

        $users = User::all();
        $slots = ParkingSlot::all();

        // buat 20 transaksi dummy
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $slot = $slots->random();
            $entryTime = now()->subHours(rand(1, 48));
            $exitTime = rand(0, 1) ? $entryTime->copy()->addHours(rand(1, 5)) : null;
            $status = $exitTime ? 'keluar' : 'masuk';

            ParkingTransaction::create([
                'user_id' => $user->id,
                'slot_id' => $slot->id,
                'entry_time' => $entryTime,
                'exit_time' => $exitTime,
                'status' => $status,
            ]);
        }
    }
}
