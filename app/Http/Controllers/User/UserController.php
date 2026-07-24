<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;
use App\Models\RfidLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // DASHBOARD USER

    public function dashboard()
    {
        $availableSlots = ParkingSlot::where('status', 'kosong')->count();

        // 🔥 TAMBAHKAN INI
        $slots = ParkingSlot::with('area')->get();

        $logs = RfidLog::with(['rfidCard'])
            ->where('user_id', auth()->id())
            ->latest('logged_at')
            ->take(5)
            ->get();

        return view('dashboard.user', compact(
            'availableSlots',
            'logs',
            'slots' // 🔥 penting!
        ));
    }

    // HISTORY PARKIR
    public function history()
    {
        $logs = RfidLog::with(['rfidCard', 'slot.area'])
            ->where('user_id', auth()->id())
            ->latest('logged_at')
            ->paginate(10)
            ->withQueryString();

        return view('pengguna.history', compact('logs'));
    }

    // FORM PASSWORD
    public function password()
    {
        return view('pengguna.password');
    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('pengguna.dashboard')
            ->with('success', 'Password berhasil diubah');
    }
}
