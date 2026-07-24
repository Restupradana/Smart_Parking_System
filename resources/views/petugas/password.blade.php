@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')

    <div class="max-w-2xl mx-auto">

        <!-- HEADER -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                🔐 <span>Ganti Password</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Pastikan password baru aman dan mudah diingat
            </p>
        </div>

        <!-- ALERT SUCCESS -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- VALIDATION ERROR -->
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- CARD -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">

            <form method="POST" action="{{ route('petugas.updatePassword') }}" class="space-y-6">
                @csrf

                <!-- PASSWORD LAMA -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Lama
                    </label>

                    <input type="password" name="current_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                       focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       transition outline-none" placeholder="Masukkan password lama" required>
                </div>

                <!-- PASSWORD BARU -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru
                    </label>

                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                       focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       transition outline-none" placeholder="Masukkan password baru" required>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50
                                       focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       transition outline-none" placeholder="Ulangi password baru" required>
                </div>

                <!-- ACTION BUTTON -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">

                    <a href="{{ route('petugas.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                        ← Kembali
                    </a>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl
                                       shadow-md hover:shadow-lg transition font-semibold">
                        💾 Simpan Password
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection