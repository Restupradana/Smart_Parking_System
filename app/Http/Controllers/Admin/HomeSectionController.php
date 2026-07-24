<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSectionController extends Controller
{
    // Landing Page
    public function showHome()
    {
        $section = HomeSection::first();

        return view('welcome', compact('section'));
    }

    // Form Edit
    public function edit()
    {
        $section = HomeSection::firstOrCreate([], [
            'title' => 'Smart Parking System',
            'description' => 'Cari parkir tanpa pusing dengan teknologi IoT',
            'image_path' => '',
            'logo_path' => '',
        ]);

        return view('admin.edit-home', compact('section'));
    }

    // Update Data
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $section = HomeSection::firstOrCreate([]);

        // Upload gambar utama
        if ($request->hasFile('image_path')) {

            if ($section->image_path && Storage::exists('public/'.$section->image_path)) {
                Storage::delete('public/'.$section->image_path);
            }

            $section->image_path = $request->file('image_path')->store('home', 'public');
        }

        // Upload logo
        if ($request->hasFile('logo_path')) {

            if ($section->logo_path && Storage::exists('public/'.$section->logo_path)) {
                Storage::delete('public/'.$section->logo_path);
            }

            $section->logo_path = $request->file('logo_path')->store('logo', 'public');
        }

        // Update text
        $section->title = $request->title;
        $section->description = $request->description;

        $section->save();

        return redirect()->route('admin.home.edit')->with('success', 'Landing page berhasil diperbarui!');
    }
}
