<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\RfidCard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * =========================================================
     * LIST USER
     * =========================================================
     */
    public function index()
    {
        $users = User::with(['role', 'rfidCards'])
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->orderByRaw("
                CASE
                    WHEN roles.name = 'admin' THEN 1
                    WHEN roles.name = 'petugas' THEN 2
                    WHEN roles.name = 'pengguna' THEN 3
                    ELSE 4
                END
            ")
            ->select('users.*')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * =========================================================
     * FORM CREATE
     * =========================================================
     */
    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * =========================================================
     * STORE USER + RFID
     * =========================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'rfid_tag'  => 'required|string|unique:rfid_cards,uid',
            'role_id'   => 'required|exists:roles,id',
            'is_active' => 'required|boolean'
        ]);

        DB::beginTransaction();

        try {

            // =====================================================
            // SIMPAN USER
            // =====================================================

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt(str()->random(8)),
                'role_id' => $request->role_id
            ]);

            // =====================================================
            // SIMPAN RFID
            // =====================================================

            RfidCard::create([
                'user_id' => $user->id,
                'uid' => strtoupper(trim($request->rfid_tag)),
                'is_active' => $request->is_active,
                'registered_at' => now()
            ]);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', '✅ User berhasil ditambahkan');
        } catch (\Exception $e) {

            DB::rollback();

            \Log::error('STORE USER ERROR : ' . $e->getMessage());

            return back()
                ->with('error', '❌ Gagal tambah user')
                ->withInput();
        }
    }

    /**
     * =========================================================
     * FORM EDIT
     * =========================================================
     */
    public function edit($id)
    {
        $user = User::with('rfidCards')->findOrFail($id);

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * =========================================================
     * UPDATE USER + RFID
     * =========================================================
     */
    public function update(Request $request, $id)
    {
        $user = User::with('rfidCards')->findOrFail($id);

        $rfidId = optional($user->rfidCards->first())->id;

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'rfid_tag'  => 'required|string|unique:rfid_cards,uid,' . $rfidId,
            'role_id'   => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
            'password'  => 'nullable|min:6|confirmed'
        ]);

        DB::beginTransaction();

        try {

            // =====================================================
            // UPDATE USER
            // =====================================================

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ];

            // UPDATE PASSWORD
            if ($request->filled('password')) {

                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // =====================================================
            // UPDATE / CREATE RFID
            // =====================================================

            $card = $user->rfidCards->first();

            if ($card) {

                // UPDATE RFID
                $card->update([
                    'uid' => strtoupper(trim($request->rfid_tag)),
                    'is_active' => $request->is_active
                ]);

            } else {

                // CREATE RFID JIKA BELUM ADA
                RfidCard::create([
                    'user_id' => $user->id,
                    'uid' => strtoupper(trim($request->rfid_tag)),
                    'is_active' => $request->is_active,
                    'registered_at' => now()
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', '✅ User berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollback();

            \Log::error('UPDATE USER ERROR : ' . $e->getMessage());

            return back()
                ->with('error', '❌ Gagal update user : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * =========================================================
     * DELETE USER
     * =========================================================
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // HAPUS RFID TERLEBIH DAHULU
        RfidCard::where('user_id', $user->id)->delete();

        // HAPUS USER
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', '✅ User berhasil dihapus');
    }
}