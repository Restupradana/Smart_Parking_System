<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParkingSlot;
use App\Models\RfidLog;
use App\Models\ParkingArea;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalSlots = ParkingSlot::count();
        $availableSlots = ParkingSlot::where('status', 'kosong')->count();
        $occupiedSlots = ParkingSlot::where('status', 'terisi')->count();

        // 🔥 eager load area biar tidak N+1 query
        $slots = ParkingSlot::with('area')
            ->orderByRaw("
        substr(slot_number, 1, instr(slot_number, '-') - 1) ASC,
        CAST(substr(slot_number, instr(slot_number, '-') + 1) AS INTEGER) ASC
    ")
            ->get();

        // 🔥 ambil 5 data terbaru untuk dashboard
        $rfidLogs = RfidLog::with(['user', 'rfidCard', 'slot.area'])
            ->latest('logged_at')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalSlots',
            'availableSlots',
            'occupiedSlots',
            'slots',
            'rfidLogs'
        ));
    }

    /**
     * HISTORY RFID (ADMIN)
     */
    public function history(Request $request)
    {
        $query = RfidLog::with(['user', 'rfidCard', 'slot.area']);

        // 🔍 FILTER USER
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 FILTER AREA
        if ($request->filled('area_id')) {
            $query->whereHas('slot', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }

        // 🔥 PAGINATION + KEEP FILTER
        $logs = $query->latest('logged_at')
            ->paginate(10)
            ->withQueryString(); // penting supaya filter tidak hilang

        // DATA DROPDOWN
        $users = User::all();
        $areas = ParkingArea::all();

        return view('admin.history', compact('logs', 'users', 'areas'));
    }

    /**
     * Dashboard Petugas
     */
    public function petugasDashboard()
    {
        return view('petugas.dashboard');
    }

    /**
     * Dashboard User
     */
    public function userDashboard()
    {
        return view('dashboard.user');
    }
}
