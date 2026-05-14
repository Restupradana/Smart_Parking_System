@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                👨‍💼 Dashboard Petugas
            </h1>

            <p class="text-gray-500 mt-1">
                Monitoring realtime status parkir dan kendaraan
            </p>
        </div>

        <!-- STATUS -->
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
            🟢 Sistem Aktif
        </div>

    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))

        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>

    @endif

    <!-- SUMMARY CARD -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- TOTAL SLOT -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">

                <div>
                    <p class="text-sm text-gray-500">
                        Total Slot
                    </p>

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
                    <p class="text-sm text-gray-500">
                        Slot Terisi
                    </p>

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
                    <p class="text-sm text-gray-500">
                        Slot Kosong
                    </p>

                    <h2 class="text-3xl font-bold text-green-500 mt-2">
                        {{ $availableSlots }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                    ✅
                </div>

            </div>

        </div>

        <!-- PERSENTASE -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-lg transition">

            <div class="flex justify-between items-center">

                <div>
                    <p class="text-sm text-gray-500">
                        Persentase Kosong
                    </p>

                    <h2 class="text-3xl font-bold text-blue-500 mt-2">
                        {{ $totalSlots > 0 ? round(($availableSlots / $totalSlots) * 100) : 0 }}%
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                    📈
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
                            📊 Kapasitas Parkir
                        </h3>

                        <p class="text-sm text-gray-500">
                            Monitoring kapasitas slot realtime
                        </p>

                    </div>

                    <div class="text-right">

                        <p class="text-sm text-gray-400">
                            Slot Tersedia
                        </p>

                        <h4 class="font-bold text-green-500 text-xl">
                            {{ $availableSlots }}/{{ $totalSlots }}
                        </h4>

                    </div>

                </div>

                <!-- PROGRESS BAR -->
                <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">

                    <div
                        class="bg-gradient-to-r from-green-400 to-green-600 h-4 rounded-full transition-all duration-500"
                        style="width: {{ $totalSlots > 0 ? ($availableSlots / $totalSlots) * 100 : 0 }}%">
                    </div>

                </div>

                <div class="flex justify-between text-xs text-gray-500 mt-2">

                    <span>Penuh</span>
                    <span>Kosong</span>

                </div>

            </div>

            <!-- DENAH -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex justify-between items-center mb-5">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            🚘 Monitoring Slot Parkir
                        </h3>

                        <p class="text-sm text-gray-500">
                            Status realtime dari sensor ESP32
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

                <!-- SLOT -->
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

            <!-- INFO PANEL -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    📡 Informasi Sistem
                </h3>

                <div class="space-y-4">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-500">
                            Status Server
                        </span>

                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            ONLINE
                        </span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-500">
                            Sensor ESP32
                        </span>

                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                            AKTIF
                        </span>

                    </div>

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-500">
                            Monitoring
                        </span>

                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                            REALTIME
                        </span>

                    </div>

                </div>

            </div>

            <!-- PETUNJUK -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-sm p-6 text-white">

                <h3 class="text-lg font-bold mb-3">
                    💡 Petunjuk Petugas
                </h3>

                <ul class="space-y-2 text-sm opacity-90">

                    <li>
                        ✅ Pastikan sensor parkir aktif
                    </li>

                    <li>
                        🚗 Monitor kendaraan masuk/keluar
                    </li>

                    <li>
                        📡 Pastikan koneksi ESP32 stabil
                    </li>

                    <li>
                        🔄 Dashboard refresh otomatis
                    </li>

                </ul>

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