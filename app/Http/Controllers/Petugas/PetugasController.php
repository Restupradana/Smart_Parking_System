<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\RfidLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    // DASHBOARD
    public function dashboard()
    {
        $totalSlots = ParkingSlot::count();
        $availableSlots = ParkingSlot::where('status', 'kosong')->count();
        $slots = ParkingSlot::with('area')->get();

        return view('petugas.dashboard', compact(
            'totalSlots',
            'availableSlots',
            'slots'
        ));
    }

    // MONITORING SLOT
    public function monitoring()
    {
        $slots = ParkingSlot::with('area')->get();

        return view('petugas.monitoring', compact('slots'));
    }

    // GATE CONTROL
    public function gate()
    {
        return view('petugas.gate');
    }

    // HISTORY RFID
    public function history(Request $request)
    {
        $query = RfidLog::with(['user', 'rfidCard', 'slot.area']);

        // 🔍 FILTER USER
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 FILTER AREA (LEWAT SLOT)
        if ($request->area_id) {
            $query->whereHas('slot', function ($q) use ($request) {
                $q->where('area_id', $request->area_id);
            });
        }

        $logs = $query->latest('logged_at')->get();

        // DATA UNTUK DROPDOWN
        $users = \App\Models\User::all();
        $areas = \App\Models\ParkingArea::all();

        return view('petugas.history', compact('logs', 'users', 'areas'));
    }

    // FORM PASSWORD
    public function password()
    {
        return view('petugas.password');
    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}
