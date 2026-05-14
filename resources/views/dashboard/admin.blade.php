@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                📊 Dashboard Smart Parking
            </h1>

            <p class="text-gray-500 mt-1">
                Monitoring realtime parkir, RFID, dan aktivitas pengguna
            </p>
        </div>

        <!-- STATUS REALTIME -->
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
            🟢 Sistem Online
        </div>

    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- SUMMARY CARD -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- TOTAL USER -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total User</p>

                    <h2 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalUsers }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                    👥
                </div>
            </div>

        </div>

        <!-- TOTAL SLOT -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Slot</p>

                    <h2 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalSlots }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-2xl">
                    🅿️
                </div>
            </div>

        </div>

        <!-- SLOT TERISI -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Slot Terisi</p>

                    <h2 class="text-3xl font-bold text-red-500 mt-2">
                        {{ $totalSlots - $availableSlots }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center text-2xl">
                    🚗
                </div>
            </div>

        </div>

        <!-- SLOT KOSONG -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Slot Kosong</p>

                    <h2 class="text-3xl font-bold text-green-500 mt-2">
                        {{ $availableSlots }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                    ✅
                </div>
            </div>

        </div>

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
                            Monitoring kapasitas realtime
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-gray-400">
                            Persentase Kosong
                        </p>

                        <h4 class="font-bold text-green-500 text-xl">
                            {{ $totalSlots > 0 ? round(($availableSlots / $totalSlots) * 100) : 0 }}%
                        </h4>
                    </div>

                </div>

                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">

                    <div
                        id="progress-bar"
                        class="bg-gradient-to-r from-green-400 to-green-600 h-4 rounded-full transition-all duration-500"
                        style="width: {{ $totalSlots > 0 ? ($availableSlots / $totalSlots) * 100 : 0 }}%">
                    </div>

                </div>

                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>Kapasitas Penuh</span>
                    <span>Kapasitas Tersedia</span>
                </div>

            </div>

            <!-- DENAH PARKIR -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex justify-between items-center mb-5">

                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            🚘 Denah Slot Parkir
                        </h3>

                        <p class="text-sm text-gray-500">
                            Status slot realtime dari sensor ESP32
                        </p>
                    </div>

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

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    @foreach($slots as $slot)

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

                    @endforeach

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-6">

            <!-- RFID HISTORY -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex justify-between items-center mb-5">

                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            📡 Aktivitas RFID
                        </h3>

                        <p class="text-sm text-gray-500">
                            Log realtime akses kendaraan
                        </p>
                    </div>

                    <a href="{{ route('admin.history') }}"
                       class="text-sm text-blue-500 hover:text-blue-700 font-medium">
                        Lihat Semua →
                    </a>

                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">

                    @forelse($rfidLogs as $log)

                        <div class="border border-gray-100 rounded-2xl p-4 hover:bg-gray-50 transition">

                            <div class="flex justify-between items-start">

                                <div class="flex gap-3">

                                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-lg">
                                        👤
                                    </div>

                                    <div>

                                        <h4 class="font-semibold text-gray-800">
                                            {{ $log->user->name ?? 'Unknown User' }}
                                        </h4>

                                        <p class="text-xs text-gray-500 mt-1">
                                            UID:
                                            {{ $log->rfidCard->uid ?? '-' }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            {{ $log->slot->slot_number ?? '-' }}
                                            •
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
                                        {{ $log->logged_at->format('d M Y') }}
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        {{ $log->logged_at->format('H:i') }}
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
                                RFID log akan muncul realtime di sini
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

    }, 10000);

</script>

@endsection