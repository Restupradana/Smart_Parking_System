<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParkingSlot;

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

        $slots = ParkingSlot::orderBy('slot_number')->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalSlots',
            'availableSlots',
            'slots'
        ));
        
    }

    /**
     * Dashboard Petugas
     */
    public function petugasDashboard()
    {
        return view('dashboard.petugas');
    }

    /**
     * Dashboard User
     */
    public function userDashboard()
    {
        return view('dashboard.user');
    }
}