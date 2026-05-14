<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\SensorController;

/*
|--------------------------------------------------------------------------
| API Routes - Smart Parking System
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ================= RFID =================

    /**
     * 🔥 RFID GATE (AKSES MASUK/KELUAR)
     * Digunakan saat kartu ditempel di gate
     * → logic masuk ke controller (pakai DB & rfid_logs)
     */
    Route::post('/rfid', [RfidController::class, 'checkRFID']);


    /**
     * 🔥 RFID SCAN (UNTUK FORM TAMBAH USER)
     * ESP32 kirim UID ke sini
     * → TIDAK DISIMPAN KE DATABASE
     * → hanya disimpan sementara (cache)
     */
    Route::post('/rfid/scan', function (Request $request) {

        $request->validate([
            'rfid_tag' => 'required|string'
        ]);

        // simpan ke cache selama 15 detik
        Cache::put('latest_rfid', $request->rfid_tag, now()->addSeconds(15));

        return response()->json([
            'status' => 'success',
            'message' => 'RFID berhasil diterima',
            'rfid_tag' => $request->rfid_tag
        ]);
    });


    /**
     * 🔥 AMBIL RFID TERAKHIR
     * Dipakai oleh button "Scan" di form admin
     */
    Route::get('/rfid/latest', function () {

        return response()->json([
            'status' => 'success',
            'rfid_tag' => Cache::get('latest_rfid') // bisa null kalau belum scan
        ]);
    });


    // ================= SENSOR =================

    /**
     * 🔥 SENSOR PARKIR (ULTRASONIK)
     * Update status slot parkir
     */
    Route::post('/sensor', [SensorController::class, 'store']);

});