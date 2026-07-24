<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingArea;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ParkingSlotController extends Controller
{
    public function index(Request $request)
    {
        $query = ParkingSlot::with('area');

        // 🔥 SORTING CUSTOM (A-1, A-2, A-10)
        $query->orderByRaw("
    substr(slot_number, 1, instr(slot_number, '-') - 1) ASC,
    CAST(substr(slot_number, instr(slot_number, '-') + 1) AS UNSIGNED) ASC ");

        $slots = $query->paginate(10)->withQueryString();

        return view('admin.parking_slots.index', compact('slots'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        $areas = ParkingArea::all();

        return view('admin.parking_slots.create', compact('areas'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:parking_areas,id',
            'slot_number' => 'required|string|max:255',
        ]);

        ParkingSlot::create([
            'area_id' => $request->area_id,
            'slot_number' => $request->slot_number,
            'status' => 'kosong',
        ]);

        return redirect()
            ->route('admin.parking-slots.index')
            ->with('success', 'Slot berhasil ditambahkan!');
    }

    /**
     * FORM EDIT
     */
    public function edit(ParkingSlot $parkingSlot)
    {
        $areas = ParkingArea::all();

        return view('admin.parking_slots.edit', compact('parkingSlot', 'areas'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, ParkingSlot $parkingSlot)
    {
        $request->validate([
            'area_id' => 'required|exists:parking_areas,id',
            'slot_number' => 'required|string|max:255',
            'status' => 'required|in:kosong,terisi',
        ]);

        $parkingSlot->update([
            'area_id' => $request->area_id,
            'slot_number' => $request->slot_number,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.parking-slots.index')
            ->with('success', 'Slot berhasil diupdate!');
    }

    /**
     * DELETE
     */
    public function destroy(ParkingSlot $parkingSlot)
    {
        $parkingSlot->delete();

        return redirect()
            ->route('admin.parking-slots.index')
            ->with('success', 'Slot berhasil dihapus!');
    }
}
