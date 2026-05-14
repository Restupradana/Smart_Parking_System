@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            ✏️ Edit User
        </h2>

        <p class="text-sm text-gray-500">
            Perbarui data user dan akses RFID
        </p>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md p-8">

        <form action="{{ route('admin.users.update', $user->id) }}"
            method="POST"
            class="space-y-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- NAMA -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Nama
                    </label>

                    <input type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full border border-gray-300 p-3 rounded-lg">
                </div>

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full border border-gray-300 p-3 rounded-lg">
                </div>

                <!-- RFID -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        RFID UID
                    </label>

                    <input type="text"
                        name="rfid_tag"
                        value="{{ old('rfid_tag', optional($user->rfidCards->first())->uid) }}"
                        class="w-full border border-gray-300 p-3 rounded-lg">
                </div>

                <!-- STATUS RFID -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Status RFID
                    </label>

                    <select name="is_active"
                        class="w-full border border-gray-300 p-3 rounded-lg">

                        <option value="1"
                            {{ optional($user->rfidCards->first())->is_active == 1 ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0"
                            {{ optional($user->rfidCards->first())->is_active == 0 ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>
                </div>

                <!-- ROLE -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Role
                    </label>

                    <select name="role_id"
                        class="w-full border border-gray-300 p-3 rounded-lg">

                        @foreach($roles as $role)

                            <option value="{{ $role->id }}"
                                {{ $user->role_id == $role->id ? 'selected' : '' }}>

                                {{ ucfirst($role->name) }}

                            </option>

                        @endforeach

                    </select>
                </div>

            </div>

            <!-- PASSWORD -->
            <div class="border-t pt-6">

                <h3 class="text-lg font-semibold mb-3">
                    🔐 Ubah Password
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Password Baru
                        </label>

                        <input type="password"
                            name="password"
                            class="w-full border border-gray-300 p-3 rounded-lg">

                        <p class="text-xs text-gray-400 mt-1">
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Konfirmasi Password
                        </label>

                        <input type="password"
                            name="password_confirmation"
                            class="w-full border border-gray-300 p-3 rounded-lg">
                    </div>

                </div>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-6 border-t">

                <a href="{{ route('admin.users.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm">

                    ← Kembali

                </a>

                <button
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg shadow">

                    💾 Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection