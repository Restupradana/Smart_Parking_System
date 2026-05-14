<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifikasis')->insert([
            [
                'pengirim_id' => 2, // user/guru
                'tempat_sampah_id' => 1,
                'sensor_id' => 1,
                'pesan' => 'Tempat sampah hampir penuh.',
                'petugas_id' => 3, // petugas
                'status' => 'pending',
                'dikonfirmasi_pada' => null,
                'bukti_foto' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pengirim_id' => 2, // user/guru
                'tempat_sampah_id' => 2,
                'sensor_id' => 2,
                'pesan' => 'Segera kosongkan tempat sampah.',
                'petugas_id' => 3, // petugas
                'status' => 'dikonfirmasi',
                'dikonfirmasi_pada' => Carbon::now()->subHours(2),
                'bukti_foto' => 'bukti_foto2.jpg',
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
        ]);
    }
}
