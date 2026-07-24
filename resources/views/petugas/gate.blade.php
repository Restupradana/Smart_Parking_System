@extends('layouts.app')

@section('title', 'Kontrol Gate')

@section('sidebar-title', 'Menu Petugas')

@section('sidebar-menu')
    <li>
        <a href="{{ route('petugas.dashboard') }}"
            class="menu {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>
    </li>

    <li>
        <a href="{{ route('petugas.monitoring') }}"
            class="menu {{ request()->routeIs('petugas.monitoring') ? 'active' : '' }}">
            📡 Monitoring
        </a>
    </li>

    <li>
        <a href="{{ route('petugas.history') }}" class="menu {{ request()->routeIs('petugas.history') ? 'active' : '' }}">
            📜 History RFID
        </a>
    </li>

    <li>
        <a href="{{ route('petugas.gate') }}" class="menu {{ request()->routeIs('petugas.gate') ? 'active' : '' }}">
            🚪 Gate
        </a>
    </li>
@endsection


@section('content')

    <h2 class="text-xl font-bold mb-4">🚪 Kontrol Gate</h2>

    <div class="bg-white p-6 rounded shadow">

        <p class="mb-4">Gunakan kartu RFID di gate untuk membuka akses masuk/keluar secara otomatis.</p>

        <div class="flex items-center gap-4">
            <span class="inline-block w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-sm text-gray-600">Gate aktif — tempelkan kartu RFID di reader</span>
        </div>

    </div>

@endsection