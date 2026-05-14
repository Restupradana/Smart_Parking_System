<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses permintaan login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi user
        $request->authenticate();
        $request->session()->regenerate();

        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil semua nama role user sebagai array
        $roles = $user->roles()->pluck('name')->toArray();

        if (count($roles) === 0) {
            // Jika user tidak memiliki role sama sekali
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda belum memiliki role yang valid.',
            ]);
        }

        if (count($roles) === 1) {
            // Kalau hanya satu role, simpan ke session
            session(['selected_role' => $roles[0]]);
            return redirect()->route($roles[0] . '.dashboard');
        } else {
            // Kalau lebih dari satu role, arahkan ke halaman pemilihan
            session(['available_roles' => $roles]);
            return redirect()->route('pilih.role');
        }
    }

    /**
     * Logout user dan akhiri sesi.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
