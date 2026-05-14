<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    public function index()
    {
        $accounts = User::with('roles')->latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.accounts.create', compact('roles'));
    }

    public function edit(User $account)
    {
        $roles = Role::all();
        $userRoles = $account->roles->pluck('id')->toArray();
        return view('admin.accounts.edit', compact('account', 'roles', 'userRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'wa_number' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array',
        ]);

        // Tambahkan prefix +62 jika belum ada
        $wa_number = $request->wa_number;
        if (!str_starts_with($wa_number, '+62')) {
            // Hilangkan leading 0 jika ada, lalu tambah +62
            $wa_number = preg_replace('/^0/', '', $wa_number);
            $wa_number = '+62' . $wa_number;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'wa_number' => $wa_number,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->roles);

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, User $account)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email,' . $account->id,
            'wa_number' => 'required|string|max:20',
            'password' => 'nullable|confirmed|min:6',
            'roles' => 'required|array',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // Format wa_number
        $wa_number = $data['wa_number'];
        if (!str_starts_with($wa_number, '+62')) {
            $wa_number = preg_replace('/^0/', '', $wa_number);
            $wa_number = '+62' . $wa_number;
        }

        $account->update([
            'email' => $data['email'],
            'wa_number' => $wa_number,
            'password' => $data['password'] ?? $account->password,
        ]);

        // ✅ Sinkronisasi roles multiple
        $account->roles()->sync($data['roles']);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }




    public function destroy(User $account)
    {
        $account->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
