<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RfidCard;
use App\Models\RfidLog;
use App\Models\ParkingSlot;

class RfidController extends Controller
{
    public function checkRFID(Request $request)
    {
        try {

            $request->validate([
                'rfid_tag' => 'required|string'
            ]);

            // UID dari ESP32
            $uid = strtoupper(trim($request->rfid_tag));

            // Cari kartu RFID
            $card = RfidCard::with('user')
                ->whereRaw('UPPER(uid) = ?', [$uid])
                ->first();

            // Kartu tidak ditemukan
            if (!$card) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Kartu tidak ditemukan',
                ], 404);
            }

            // Kartu nonaktif
            if (!$card->is_active) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Kartu tidak aktif',
                ], 403);
            }

            $user = $card->user;

            // Log terakhir user
            $lastLog = RfidLog::where('user_id', $user->id)
                ->latest('logged_at')
                ->first();

            // Default aksi
            $action = 'masuk';

            // Toggle masuk/keluar
            if ($lastLog && $lastLog->action === 'masuk') {
                $action = 'keluar';
            }

            // Cari slot kosong
            $slot = ParkingSlot::where('status', 'kosong')->first();

            // Simpan log RFID
            RfidLog::create([
                'user_id' => $user->id,
                'rfid_card_id' => $card->id,
                'slot_id' => $slot?->id,
                'action' => $action,
                'location' => 'Gate RFID',
                'logged_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Akses diterima',
                'user' => $user->name,
                'action' => $action,
                'uid' => $uid
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}