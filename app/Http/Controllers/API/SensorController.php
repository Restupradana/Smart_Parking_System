<?php

namespace App\Http\Controllers\Api;

use App\Events\ParkingUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SensorUpdateRequest;
use App\Models\ParkingSlot;
use App\Models\Sensor;

class SensorController extends Controller
{
    public function store(SensorUpdateRequest $request)
    {
        try {

            // Cari slot
            $slot = ParkingSlot::where(
                'slot_number',
                trim($request->slot_number)
            )->first();

            // Slot tidak ditemukan
            if (! $slot) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Slot tidak ditemukan',
                ], 404);
            }

            // Update slot parkir
            $slot->update([
                'status' => $request->status,
                'last_updated' => now(),
            ]);

            // Cari sensor berdasarkan slot
            $sensor = Sensor::where('slot_id', $slot->id)->first();

            // Jika sensor belum ada
            if (! $sensor) {

                Sensor::create([
                    'slot_id' => $slot->id,
                    'type' => 'ultrasonic',
                    'last_status' => $request->status,
                    'last_read_at' => now(),
                ]);

            } else {

                // Update sensor
                $sensor->update([
                    'last_status' => $request->status,
                    'last_read_at' => now(),
                ]);
            }

            event(new ParkingUpdated($slot));

            return response()->json([
                'status' => 'success',
                'message' => 'Sensor berhasil diupdate',
                'slot' => $slot->slot_number,
                'status_slot' => $request->status,
            ]);

        } catch (\Exception $e) {

            \Log::error('Sensor update error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server',
            ], 500);
        }
    }
}
