<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'wa_number' => ['required', 'string', 'max:20'],
        ]);

        // Normalisasi wa_number
        $waInput = preg_replace('/[^0-9]/', '', $request->wa_number); // Hanya angka
        $waNumber = ltrim($waInput, '0'); // Hilangkan angka 0 di awal jika ada
        $waNumber = '+62' . $waNumber;

        // Simpan user ke tabel `users`
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'wa_number' => $waNumber,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
        ]);

        // Cari role_id untuk role 'user'
        $roleId = DB::table('roles')->where('name', 'user')->value('id');

        // Simpan ke tabel `user_roles`
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new Registered($user));

        // Muat ulang user beserta relasi userRole dan role sebelum login
        $user = $user->fresh(['userRole', 'userRole.role']);

        // Login user
        Auth::login($user);

        // Simpan role ke session
        session(['selected_role' => $user->userRole->role->name]);


        return redirect()->route('user.dashboard');
    }
}
