<?php

use App\Http\Controllers\Api\RfidController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Requests\Api\RfidScanRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Smart Parking System
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ================= RFID =================

    /**
     * RFID GATE (AKSES MASUK/KELUAR)
     * Digunakan saat kartu ditempel di gate
     * → logic masuk ke controller (pakai DB & rfid_logs)
     */
    Route::post('/rfid', [RfidController::class, 'checkRFID'])
        ->middleware('throttle:60,1');

    /**
     * RFID SCAN (UNTUK FORM TAMBAH USER)
     * ESP32 kirim UID ke sini
     * → TIDAK DISIMPAN KE DATABASE
     * → hanya disimpan sementara (cache)
     */
    Route::post('/rfid/scan', function (RfidScanRequest $request) {

        // simpan ke cache selama 15 detik
        Cache::put('latest_rfid', $request->rfid_tag, now()->addSeconds(15));

        return response()->json([
            'status' => 'success',
            'message' => 'RFID berhasil diterima',
            'rfid_tag' => $request->rfid_tag,
        ]);
    })->middleware('throttle:30,1');

    /**
     * AMBIL RFID TERAKHIR
     * Dipakai oleh button "Scan" di form admin
     */
    Route::get('/rfid/latest', function () {

        return response()->json([
            'status' => 'success',
            'rfid_tag' => Cache::get('latest_rfid'),
        ]);
    })->middleware('throttle:30,1');

    // ================= SENSOR =================

    /**
     * SENSOR PARKIR (ULTRASONIK)
     * Update status slot parkir
     */
    Route::post('/sensor', [SensorController::class, 'store'])
        ->middleware('throttle:60,1');

});
