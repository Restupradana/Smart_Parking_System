@extends('layouts.app')

@section('title', 'Riwayat Parkir')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        📡 <span>Riwayat Parkir</span>
    </h2>
    <p class="text-sm text-gray-500 mt-1">
        Monitoring aktivitas masuk dan keluar kendaraan
    </p>
</div>

<!-- CARD -->
<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm text-left">

            <!-- HEADER -->
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="p-4">RFID</th>
                    <th class="p-4">Aksi</th>
                    <th class="p-4">Slot</th>
                    <th class="p-4">Lokasi</th>
                    <th class="p-4">Waktu</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody class="divide-y divide-gray-100">

                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- RFID -->
                        <td class="p-4 font-medium text-gray-700">
                            {{ $log->rfidCard->uid ?? '-' }}
                        </td>

                        <!-- AKSI -->
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold text-white
                                {{ $log->action == 'masuk' ? 'bg-green-500' : 
                                   ($log->action == 'keluar' ? 'bg-blue-500' : 'bg-red-500') }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>

                        <!-- SLOT -->
                        <td class="p-4 text-gray-700">
                            {{ $log->slot->slot_number ?? '-' }}
                        </td>

                        <!-- LOKASI -->
                        <td class="p-4 text-gray-500">
                            {{ $log->slot->area->location ?? $log->location ?? '-' }}
                        </td>

                        <!-- WAKTU -->
                        <td class="p-4 text-gray-500 text-sm">
                            {{ $log->logged_at ? $log->logged_at->format('d M Y, H:i') : '-' }}
                        </td>

                    </tr>
                @empty

                    <tr>
                        <td colspan="5" class="text-center p-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl">📭</span>
                                <p class="mt-2">Belum ada riwayat parkir</p>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    @if($logs->hasPages())
        <div class="p-4 border-t bg-gray-50">
            {{ $logs->links() }}
        </div>
    @endif

</div>

@endsection