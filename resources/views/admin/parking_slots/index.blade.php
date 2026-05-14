@extends('layouts.app')

@section('title', 'Manajemen Slot Parkir')

@section('content')

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold">🚗 Manajemen Slot Parkir</h2>
                <p class="text-gray-500 text-sm">Kelola dan monitor status slot parkir</p>
            </div>

            <a href="{{ route('admin.parking-slots.create') }}"
                class="mt-3 md:mt-0 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow transition">
                + Tambah Slot
            </a>
        </div>

        <!-- NOTIF -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                ✔ {{ session('success') }}
            </div>
        @endif

        <!-- LEGEND -->
        <div class="flex items-center gap-4 mb-6 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-green-500 rounded"></div>
                <span>Kosong</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-red-500 rounded"></div>
                <span>Terisi</span>
            </div>
        </div>

        <!-- GRID SLOT -->
        <div class="bg-white p-6 rounded-xl shadow mb-8">
            <h3 class="font-semibold mb-4 text-gray-700">Visual Slot Parkir</h3>

            <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-10 gap-4">
                @foreach($slots as $slot)
                    <div class="relative group">

                        <!-- SLOT CARD -->
                        <div class="p-4 text-center rounded-xl text-white font-semibold shadow
                                transition transform hover:scale-105 cursor-pointer
                                {{ $slot->status === 'kosong' ? 'bg-green-500' : 'bg-red-500' }}">

                            <div class="text-lg">{{ $slot->slot_number }}</div>
                            <div class="text-xs mt-1">{{ $slot->area->name }}</div>
                        </div>

                        <!-- HOVER ACTION -->
                        <div class="absolute inset-0 bg-black/60 rounded-xl flex flex-col items-center justify-center 
                                        opacity-0 group-hover:opacity-100 transition">

                            <p class="text-white text-xs mb-1">{{ $slot->area->location }}</p>
                            <p class="text-white text-xs mb-2">{{ ucfirst($slot->status) }}</p>

                            <div class="flex gap-2">
                                <a href="{{ route('admin.parking-slots.edit', $slot->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                    Edit
                                </a>

                                <form action="{{ route('admin.parking-slots.destroy', $slot->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus slot ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <!-- TABLE DETAIL -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <!-- HEADER -->
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-700">Detail Data Slot</h3>
                <span class="text-sm text-gray-500">
                    Total: {{ $slots->total() }} data
                </span>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Slot</th>
                            <th class="p-3 text-left">Area</th>
                            <th class="p-3 text-left">Lokasi</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($slots as $index => $slot)
                            <tr class="border-b hover:bg-gray-50 transition">

                                <td class="p-3">
                                    {{ ($slots->currentPage() - 1) * $slots->perPage() + $loop->iteration }}
                                </td>

                                <td class="p-3 font-semibold">
                                    {{ $slot->slot_number }}
                                </td>

                                <td class="p-3">
                                    {{ $slot->area->name }}
                                </td>

                                <td class="p-3 text-gray-500">
                                    {{ $slot->area->location }}
                                </td>

                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded text-white
                                        {{ $slot->status === 'kosong' ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </td>

                                <td class="p-3 flex gap-2">
                                    <a href="{{ route('admin.parking-slots.edit', $slot->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.parking-slots.destroy', $slot->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus slot ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-6 text-gray-500">
                                    Tidak ada data slot
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-4 flex flex-col md:flex-row justify-between items-center gap-3">

                <!-- INFO -->
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $slots->firstItem() }} - {{ $slots->lastItem() }}
                    dari {{ $slots->total() }} data
                </div>

                <!-- LINKS -->
                <div>
                    {{ $slots->onEachSide(1)->links('pagination::tailwind') }}
                </div>

            </div>

        </div>

    </div>

@endsection