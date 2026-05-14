<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectionController extends Controller
{
    /**
     * Tampilkan halaman pilih role.
     */
    public function show()
    {
        $user = Auth::user();
        $roles = $user->roles()->pluck('name')->toArray();

        if (count($roles) <= 1) {
            // Kalau cuma 1 role, langsung redirect ke dashboard role tersebut
            return redirect()->route($roles[0] . '.dashboard');
        }

        return view('auth.select-role', compact('roles'));
    }

    /**
     * Simpan role yang dipilih user ke session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $user = Auth::user();
        $roles = $user->roles()->pluck('name')->toArray();

        $selectedRole = $request->input('role');

        // Pastikan role yang dipilih ada di daftar role user
        if (!in_array($selectedRole, $roles)) {
            return back()->withErrors(['role' => 'Role tidak valid.']);
        }

        // Simpan role ke session
        session(['selected_role' => $selectedRole]);

        // Redirect ke dashboard sesuai role yang dipilih
        return redirect()->route($selectedRole . '.dashboard');
    }
}
