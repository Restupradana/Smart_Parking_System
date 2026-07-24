<?php

namespace Database\Seeders;

use App\Models\RfidCard;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RFIDCardSeeder extends Seeder
{
    public function run(): void
    {
        if (RfidCard::count() > 0) {
            return;
        }

        $users = User::all();

        foreach ($users as $user) {

            RfidCard::create([
                'user_id' => $user->id,
                'uid' => strtoupper(Str::random(8)),
                'is_active' => true,
            ]);
        }
    }
}
