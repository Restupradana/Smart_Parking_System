<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RfidCheckRequest;

use App\Models\RfidCard;
use App\Models\RfidLog;
use App\Models\ParkingSlot;
use App\Events\ParkingUpdated;

class RfidController extends Controller
{
    public function checkRFID(RfidCheckRequest $request)
    {
        try {

            $uid  = strtoupper(trim($request->rfid_tag));
            $gate = $request->gate;

            $card = RfidCard::with('user')
                ->whereRaw('UPPER(uid)=?', [$uid])
                ->first();

            if (!$card) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kartu tidak terdaftar'
                ], 404);
            }

            if (!$card->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kartu tidak aktif'
                ], 403);
            }

            $user = $card->user;

            $lastLog = RfidLog::where('user_id', $user->id)
                ->latest('logged_at')
                ->first();

            if ($gate == 'masuk') {

                if ($lastLog && $lastLog->action == 'masuk') {

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kendaraan masih berada di area parkir'
                    ], 403);
                }

                $action = 'masuk';
            }

            if ($gate == 'keluar') {

                if (!$lastLog || $lastLog->action == 'keluar') {

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tidak ada data parkir aktif'
                    ], 403);
                }

                $action = 'keluar';
            }

            $slot = ParkingSlot::where('status', 'kosong')->first();

            RfidLog::create([
                'user_id'      => $user->id,
                'rfid_card_id' => $card->id,
                'slot_id'      => $slot?->id,
                'action'       => $action,
                'gate'         => $gate,
                'location'     => 'Gate RFID',
                'logged_at'    => now()
            ]);

            if ($slot) {
                event(new ParkingUpdated($slot));
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Akses diterima',
                'user'    => $user->name,
                'action'  => $action,
                'gate'    => $gate
            ]);

        } catch (\Exception $e) {

            \Log::error('RFID gate error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
}