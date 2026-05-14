@extends('layouts.app')

@section('title', 'Manajemen Area Parkir')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">🚗 Manajemen Area Parkir</h2>
            <p class="text-gray-500 text-sm">Kelola area parkir sistem</p>
        </div>
    </div>

    <!-- FORM TAMBAH AREA -->
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <h3 class="font-semibold mb-4 text-gray-700">Tambah Area Parkir</h3>

        <form method="POST" action="{{ route('admin.parking-areas.store') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @csrf

            <input 
                name="name" 
                placeholder="Nama Area (contoh: A-1)" 
                class="border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                required>

            <input 
                name="location" 
                placeholder="Lokasi (contoh: Depan Kampus)" 
                class="border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                required>

            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold transition">
                ➕ Tambah
            </button>

        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">Daftar Area Parkir</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Area</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($areas as $index => $area)
                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="p-3">{{ $index + 1 }}</td>

                            <td class="p-3 font-semibold">
                                {{ $area->name }}
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $area->location ?? '-' }}
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center gap-2">

                                    <!-- EDIT -->
                                    <a href="{{ route('admin.parking-areas.edit', $area->id) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">
                                        ✏️ Edit
                                    </a>

                                    <!-- DELETE -->
                                    <form action="{{ route('admin.parking-areas.destroy', $area->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs"
                                            onclick="return confirm('Yakin hapus area ini?')">
                                            🗑 Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-6 text-gray-500">
                                🚫 Belum ada data area parkir
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection