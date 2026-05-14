@extends('layouts.app')

@section('title', 'Tambah Slot Parkir')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">🚗 Tambah Slot Parkir</h2>

<div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-lg">

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.parking-slots.store') }}" method="POST" class="flex flex-col gap-4">
        @csrf

        <!-- Area Parkir -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Area Parkir</label>
            <select name="area_id" class="border rounded p-2 w-full focus:ring-2 focus:ring-green-400" required>
                <option value="" disabled selected>Pilih Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Nomor Slot -->
        <div>
            <label class="block mb-1 font-semibold text-gray-700">Nomor Slot</label>
            <input type="text" name="slot_number" class="border rounded p-2 w-full focus:ring-2 focus:ring-green-400" placeholder="Masukkan nomor slot" required>
        </div>

        <!-- Tombol -->
        <div class="flex gap-4 justify-end mt-4">
            <a href="{{ route('admin.parking-slots.index') }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white transition">
                Tambah
            </button>
        </div>
    </form>
</div>
@endsection