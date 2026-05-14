<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingArea;

class ParkingAreaController extends Controller
{
    public function index()
    {
        $areas = ParkingArea::all()->sort(function($a, $b){
            preg_match('/([A-Z]+)-(\d+)/', $a->name, $ma);
            preg_match('/([A-Z]+)-(\d+)/', $b->name, $mb);
            $letterA = $ma[1] ?? '';
            $numA = isset($ma[2]) ? (int)$ma[2] : 0;
            $letterB = $mb[1] ?? '';
            $numB = isset($mb[2]) ? (int)$mb[2] : 0;
            return $letterA === $letterB ? $numA <=> $numB : strcmp($letterA, $letterB);
        });

        return view('admin.parking_areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:parking_areas,name',
            'location' => 'required',
        ]);

        ParkingArea::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.parking-areas.index')
            ->with('success', 'Area berhasil ditambahkan!');
    }

    public function edit(ParkingArea $parkingArea)
    {
        return view('admin.parking_areas.edit', compact('parkingArea'));
    }

    public function update(Request $request, ParkingArea $parkingArea)
    {
        $request->validate([
            'name' => 'required|unique:parking_areas,name,' . $parkingArea->id,
            'location' => 'required',
        ]);

        $parkingArea->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('admin.parking-areas.index')
            ->with('success', 'Area berhasil diupdate!');
    }

    public function destroy(ParkingArea $parkingArea)
    {
        $parkingArea->delete();
        return redirect()->route('admin.parking-areas.index')
            ->with('success', 'Area berhasil dihapus!');
    }
}