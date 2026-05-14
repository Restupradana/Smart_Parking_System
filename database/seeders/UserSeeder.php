<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin Smart Parking',
                'email' => 'admin@parkir.com',
                'wa_number' => '081234567890',
                'password' => Hash::make('password'),
                'rfid_tag' => 'RFID001',
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas Parkir',
                'email' => 'petugas@parkir.com',
                'wa_number' => '081234567891',
                'password' => Hash::make('password'),
                'rfid_tag' => 'RFID002',
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahasiswa User',
                'email' => 'user@parkir.com',
                'wa_number' => '081234567892',
                'password' => Hash::make('password'),
                'rfid_tag' => 'RFID003',
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}