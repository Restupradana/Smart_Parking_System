<?php

namespace App\Http\Controllers\Api;

use App\Events\ParkingUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RfidCheckRequest;
use App\Models\ParkingSlot;
use App\Models\ParkingTransaction;
use App\Models\RfidCard;
use App\Models\RfidLog;
use Illuminate\Support\Facades\DB;

class RfidController extends Controller
{
    public function checkRFID(RfidCheckRequest $request)
    {
        try {
            $uid = strtoupper(trim($request->rfid_tag));
            $gate = $request->gate;

            $card = RfidCard::with('user')
                ->whereRaw('UPPER(uid)=?', [$uid])
                ->first();

            if (! $card) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kartu tidak terdaftar',
                ], 404);
            }

            if (! $card->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kartu tidak aktif',
                ], 403);
            }

            $user = $card->user;

            $lastLog = RfidLog::where('user_id', $user->id)
                ->latest('logged_at')
                ->first();

            if ($gate === 'masuk') {
                if ($lastLog && $lastLog->action === 'masuk') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kendaraan masih berada di area parkir',
                    ], 403);
                }

                return $this->handleEntry($user, $card);
            }

            if ($gate === 'keluar') {
                if (! $lastLog || $lastLog->action === 'keluar') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tidak ada data parkir aktif',
                    ], 403);
                }

                return $this->handleExit($user, $card, $lastLog);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gate tidak valid',
            ], 400);

        } catch (\Exception $e) {
            \Log::error('RFID gate error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }

    /**
     * Proses kendaraan masuk: assign slot kosong, update status, buat transaksi.
     */
    private function handleEntry($user, $card)
    {
        return DB::transaction(function () use ($user, $card) {
            $slot = ParkingSlot::where('status', 'kosong')->first();

            if (! $slot) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada slot parkir yang tersedia',
                ], 409);
            }

            $slot->update(['status' => 'terisi', 'last_updated' => now()]);

            RfidLog::create([
                'user_id' => $user->id,
                'rfid_card_id' => $card->id,
                'slot_id' => $slot->id,
                'action' => 'masuk',
                'gate' => 'masuk',
                'location' => 'Gate RFID',
                'logged_at' => now(),
            ]);

            ParkingTransaction::create([
                'user_id' => $user->id,
                'slot_id' => $slot->id,
                'entry_time' => now(),
                'status' => 'active',
            ]);

            event(new ParkingUpdated($slot));

            return response()->json([
                'status' => 'success',
                'message' => 'Akses diterima',
                'user' => $user->name,
                'action' => 'masuk',
                'gate' => 'masuk',
                'slot' => $slot->slot_number,
            ]);
        });
    }

    /**
     * Proses kendaraan keluar: bebas slot, tutup transaksi.
     */
    private function handleExit($user, $card, $lastLog)
    {
        return DB::transaction(function () use ($user, $card, $lastLog) {
            $slot = $lastLog->slot;

            if ($slot) {
                $slot->update(['status' => 'kosong', 'last_updated' => now()]);
            }

            RfidLog::create([
                'user_id' => $user->id,
                'rfid_card_id' => $card->id,
                'slot_id' => $lastLog->slot_id,
                'action' => 'keluar',
                'gate' => 'keluar',
                'location' => 'Gate RFID',
                'logged_at' => now(),
            ]);

            $transaction = ParkingTransaction::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest('entry_time')
                ->first();

            if ($transaction) {
                $transaction->update([
                    'exit_time' => now(),
                    'status' => 'completed',
                ]);
            }

            if ($slot) {
                event(new ParkingUpdated($slot));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Akses diterima',
                'user' => $user->name,
                'action' => 'keluar',
                'gate' => 'keluar',
                'slot' => $slot?->slot_number,
            ]);
        });
    }
}
