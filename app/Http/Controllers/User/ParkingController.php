<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSlot;

class ParkingController extends Controller
{
    public function index()
    {
        $slots = ParkingSlot::with('parkingArea')->get();

        return view('user.slots', compact('slots'));
    }
}