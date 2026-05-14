@extends('layouts.app')

@section('title', 'History RFID')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold">📡 History RFID</h2>
        <p class="text-gray-500 text-sm">Monitoring aktivitas akses parkir</p>
    </div>

    <!-- FILTER -->
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div>
                <label class="text-xs text-gray-500">User</label>
                <select name="user_id" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Area</label>
                <select name="area_id" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-200">
                    <option value="">Semua Area</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->location }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
                    🔍 Filter
                </button>

                <a href="{{ route('admin.history') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-center w-full">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3">User</th>
                        <th class="p-3">UID</th>
                        <th class="p-3">Aksi</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3">Waktu</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="p-3 font-medium">
                                {{ $log->user->name ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $log->rfidCard->uid ?? '-' }}
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded text-white
                                    {{ $log->action == 'masuk' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>

                            <td class="p-3">
                                {{ $log->slot->area->location ?? $log->location ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-500">
                                {{ $log->logged_at->format('d M Y H:i') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-6 text-gray-500">
                                Tidak ada data history
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
        <div class="p-4 border-t bg-gray-50">
            {{ $logs->links() }}
        </div>

    </div>

</div>

@endsection