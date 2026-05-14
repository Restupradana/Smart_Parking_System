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

        <p class="mb-4">Gunakan RFID untuk membuka gate</p>

        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Buka Gate (Manual)
        </button>

    </div>

@endsection