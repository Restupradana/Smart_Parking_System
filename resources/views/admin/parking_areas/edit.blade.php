@extends('layouts.app')

@section('title', 'Edit Area Parkir')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold">🅿️ Edit Area Parkir</h2>
        <p class="text-gray-500 text-sm">
            Perbarui informasi area parkir dengan data terbaru
        </p>
    </div>

    <!-- CARD FORM -->
    <div class="bg-white p-8 rounded-xl shadow-md">

        <form action="{{ route('admin.parking-areas.update', $parkingArea->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- NAMA AREA -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-1">
                    Nama Area
                </label>
                <input type="text" name="name"
                    value="{{ old('name', $parkingArea->name) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    placeholder="Contoh: Area A"
                    required>
            </div>

            <!-- LOKASI -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-1">
                    Lokasi
                </label>
                <input type="text" name="location"
                    value="{{ old('location', $parkingArea->location) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
                    placeholder="Contoh: Gedung Utama Lt.1"
                    required>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center">

                <!-- BACK -->
                <a href="{{ route('admin.parking-areas.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm">
                    ← Kembali
                </a>

                <!-- ACTION BUTTON -->
                <div class="flex gap-3">
                    <button type="reset"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm">
                        Reset
                    </button>

                    <button type="submit"
                        class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow transition">
                        💾 Simpan Perubahan
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

@endsection