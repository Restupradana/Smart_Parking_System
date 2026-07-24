@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                👤 Dashboard Pengguna
            </h1>

            <p class="text-gray-500 mt-1">
                Monitoring slot parkir dan aktivitas RFID secara realtime
            </p>
        </div>

        <!-- STATUS -->
        <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
            🚘 Smart Parking Aktif
        </div>

    </div>

    <!-- CARD SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- SLOT TERSEDIA -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">

                <div>
                    <p class="text-sm text-gray-500">
                        Slot Tersedia
                    </p>

                    <h2 class="text-3xl font-bold text-green-500 mt-2">
                        {{ $availableSlots }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                    🅿️
                </div>

            </div>

        </div>

        <!-- HISTORY -->
        <a href="{{ route('pengguna.history') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition block">

            <div class="flex justify-between items-center">

                <div>
                    <p class="text-sm text-gray-500">
                        Riwayat Parkir
                    </p>

                    <h2 class="text-lg font-bold text-blue-500 mt-2">
                        Lihat History
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                    📡
                </div>

            </div>

        </a>

        <!-- PASSWORD -->
        <a href="{{ route('pengguna.password') }}"
           class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition block">

            <div class="flex justify-between items-center">

                <div>
                    <p class="text-sm text-gray-500">
                        Keamanan Akun
                    </p>

                    <h2 class="text-lg font-bold text-yellow-500 mt-2">
                        Ubah Password
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center text-2xl">
                    🔐
                </div>

            </div>

        </a>

    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- LEFT -->
        <div class="xl:col-span-2 space-y-6">

            <!-- KAPASITAS -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            📈 Kapasitas Parkir
                        </h3>

                        <p class="text-sm text-gray-500">
                            Persentase slot kosong realtime
                        </p>

                    </div>

                    <div class="text-right">

                        <p class="text-sm text-gray-400">
                            Kapasitas Tersedia
                        </p>

                        <h4 class="font-bold text-green-500 text-xl">
                            {{ $slots->count() > 0 ? round(($availableSlots / $slots->count()) * 100) : 0 }}%
                        </h4>

                    </div>

                </div>

                <!-- PROGRESS -->
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">

                    <div
                        class="bg-gradient-to-r from-green-400 to-green-600 h-4 rounded-full transition-all duration-500"
                        style="width: {{ $slots->count() > 0 ? ($availableSlots / $slots->count()) * 100 : 0 }}%">
                    </div>

                </div>

                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>Penuh</span>
                    <span>Tersedia</span>
                </div>

            </div>

            <!-- DENAH -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex justify-between items-center mb-5">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            🚗 Denah Area Parkir
                        </h3>

                        <p class="text-sm text-gray-500">
                            Monitoring slot parkir realtime
                        </p>

                    </div>

                    <!-- LEGEND -->
                    <div class="flex gap-3 text-xs">

                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span>Kosong</span>
                        </div>

                        <div class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span>Terisi</span>
                        </div>

                    </div>

                </div>

                @php
                    $sortedSlots = $slots->sort(function($a, $b) {

                        preg_match('/([A-Z]+)-(\d+)/', $a->slot_number, $matchA);
                        preg_match('/([A-Z]+)-(\d+)/', $b->slot_number, $matchB);

                        $letterA = $matchA[1] ?? '';
                        $numA = isset($matchA[2]) ? (int)$matchA[2] : 0;

                        $letterB = $matchB[1] ?? '';
                        $numB = isset($matchB[2]) ? (int)$matchB[2] : 0;

                        return $letterA === $letterB
                            ? $numA <=> $numB
                            : strcmp($letterA, $letterB);
                    });
                @endphp

                <!-- SLOT -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    @forelse($sortedSlots as $slot)

                        <div
                            class="rounded-2xl p-5 text-center text-white shadow-md transition hover:scale-105
                            {{ $slot->status == 'kosong'
                                ? 'bg-gradient-to-r from-green-400 to-green-600'
                                : 'bg-gradient-to-r from-red-400 to-red-600' }}">

                            <div class="text-3xl mb-2">
                                {{ $slot->status == 'kosong' ? '🅿️' : '🚗' }}
                            </div>

                            <h4 class="font-bold text-lg">
                                {{ $slot->slot_number }}
                            </h4>

                            <p class="text-xs opacity-90 mt-1">
                                {{ $slot->area->location ?? 'Area Parkir' }}
                            </p>

                            <div class="mt-3 text-xs bg-white/20 rounded-full py-1">
                                {{ strtoupper($slot->status) }}
                            </div>

                        </div>

                    @empty

                        <div class="col-span-4 text-center py-10">

                            <div class="text-5xl mb-3">
                                📭
                            </div>

                            <h3 class="font-semibold text-gray-700">
                                Belum Ada Data Slot
                            </h3>

                            <p class="text-sm text-gray-400 mt-1">
                                Slot parkir akan tampil di sini
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-6">

            <!-- HISTORY -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex justify-between items-center mb-5">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            📡 Aktivitas RFID
                        </h3>

                        <p class="text-sm text-gray-500">
                            Riwayat akses terbaru pengguna
                        </p>

                    </div>

                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">

                    @forelse($logs as $log)

                        <div class="border border-gray-100 rounded-2xl p-4 hover:bg-gray-50 transition">

                            <div class="flex justify-between items-start">

                                <div class="flex gap-3">

                                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-lg">
                                        👤
                                    </div>

                                    <div>

                                        <h4 class="font-semibold text-gray-800">
                                            {{ auth()->user()->name }}
                                        </h4>

                                        <p class="text-xs text-gray-500 mt-1">
                                            UID:
                                            {{ $log->rfidCard->uid ?? '-' }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            {{ $log->location ?? 'Gerbang Utama' }}
                                        </p>

                                    </div>

                                </div>

                                <div class="text-right">

                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold text-white
                                        {{ $log->action == 'masuk'
                                            ? 'bg-green-500'
                                            : 'bg-red-500' }}">

                                        {{ strtoupper($log->action) }}

                                    </span>

                                    <p class="text-xs text-gray-400 mt-2">
                                        {{ $log->logged_at
                                            ? $log->logged_at->format('d M Y')
                                            : '-' }}
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        {{ $log->logged_at
                                            ? $log->logged_at->format('H:i')
                                            : '-' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-10">

                            <div class="text-5xl mb-3">
                                📭
                            </div>

                            <h3 class="font-semibold text-gray-700">
                                Belum Ada Aktivitas
                            </h3>

                            <p class="text-sm text-gray-400 mt-1">
                                Riwayat RFID akan muncul di sini
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

<!-- AUTO REFRESH -->
<script>

    setInterval(() => {

        window.location.reload();

    }, 30000);

</script>

@endsection