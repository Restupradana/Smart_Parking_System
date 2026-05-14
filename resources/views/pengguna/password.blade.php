@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')

<h2 class="text-2xl font-bold mb-6">
    🔐 Ganti Password
</h2>

<!-- ALERT SUCCESS -->
@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- VALIDATION ERROR -->
@if($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white p-6 rounded-xl shadow max-w-md">

    <form method="POST" action="{{ route('pengguna.updatePassword') }}">
        @csrf

        <!-- PASSWORD BARU -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password Baru</label>
            <input type="password" name="password"
                class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                required>
        </div>

        <!-- KONFIRMASI PASSWORD -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                class="w-full border rounded p-2 focus:outline-none focus:ring focus:border-blue-300"
                required>
        </div>

        <!-- BUTTON -->
        <div class="flex justify-between items-center">

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Simpan Password
            </button>

            <a href="{{ route('pengguna.dashboard') }}"
               class="text-gray-500 hover:underline">
                Kembali
            </a>

        </div>

    </form>

</div>

@endsection