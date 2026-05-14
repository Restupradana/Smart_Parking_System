@extends('layouts.app')

@section('title', 'Monitoring Slot')

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

    <h2 class="text-xl font-bold mb-4">🚗 Monitoring Slot</h2>

    @include('petugas.partials.denah')

@endsection