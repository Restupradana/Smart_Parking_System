<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RFIDCard;
use App\Models\User;
use Illuminate\Support\Str;

class RFIDCardSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {

            RFIDCard::create([
                'user_id' => $user->id,
                'uid' => strtoupper(Str::random(8)),
                'is_active' => true,
            ]);
        }
    }
}