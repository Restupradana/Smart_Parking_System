<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ParkingSlot;
use App\Models\Sensor;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        try {

            $request->validate([
                'slot_number' => 'required|string',
                'status' => 'required|in:kosong,terisi'
            ]);

            // Cari slot
            $slot = ParkingSlot::where(
                'slot_number',
                trim($request->slot_number)
            )->first();

            // Slot tidak ditemukan
            if (!$slot) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Slot tidak ditemukan'
                ], 404);
            }

            // Update slot parkir
            $slot->update([
                'status' => $request->status,
                'last_updated' => now()
            ]);

            // Cari sensor berdasarkan slot
            $sensor = Sensor::where('slot_id', $slot->id)->first();

            // Jika sensor belum ada
            if (!$sensor) {

                Sensor::create([
                    'slot_id' => $slot->id,
                    'type' => 'ultrasonic',
                    'last_status' => $request->status,
                    'last_read_at' => now()
                ]);

            } else {

                // Update sensor
                $sensor->update([
                    'last_status' => $request->status,
                    'last_read_at' => now()
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Sensor berhasil diupdate',
                'slot' => $slot->slot_number,
                'status_slot' => $request->status
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}