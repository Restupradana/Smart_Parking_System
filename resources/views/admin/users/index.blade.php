@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">👤 Manajemen User</h2>
            <p class="text-gray-500 text-sm">Kelola pengguna & akses sistem parkir</p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow transition">
            + Tambah User
        </a>
    </div>

    <!-- 🔥 SUMMARY CARD (TANPA COMPONENT) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- TOTAL -->
        <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total User</p>
                <h3 class="text-xl font-bold">{{ $users->count() }}</h3>
            </div>
            <div class="text-2xl text-blue-500">👥</div>
        </div>

        <!-- ADMIN -->
        <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Admin</p>
                <h3 class="text-xl font-bold">
                    {{ $users->where('role.name', 'admin')->count() }}
                </h3>
            </div>
            <div class="text-2xl text-blue-600">🛠</div>
        </div>

        <!-- PETUGAS -->
        <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Petugas</p>
                <h3 class="text-xl font-bold">
                    {{ $users->where('role.name', 'petugas')->count() }}
                </h3>
            </div>
            <div class="text-2xl text-yellow-500">👷</div>
        </div>

        <!-- PENGGUNA -->
        <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Pengguna</p>
                <h3 class="text-xl font-bold">
                    {{ $users->where('role.name', 'pengguna')->count() }}
                </h3>
            </div>
            <div class="text-2xl text-gray-500">🙋</div>
        </div>

    </div>

    <!-- FILTER -->
    <div class="bg-white p-4 rounded-xl shadow flex gap-3">
        <button onclick="filterRole('all')" class="px-3 py-1 bg-gray-200 rounded">Semua</button>
        <button onclick="filterRole('admin')" class="px-3 py-1 bg-blue-500 text-white rounded">Admin</button>
        <button onclick="filterRole('petugas')" class="px-3 py-1 bg-yellow-500 text-white rounded">Petugas</button>
        <button onclick="filterRole('pengguna')" class="px-3 py-1 bg-gray-500 text-white rounded">User</button>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-4">User</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">RFID</th>
                        <th class="p-4">Role</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50 transition user-row"
                            data-role="{{ $user->role->name }}">

                            <!-- USER -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 text-white flex items-center justify-center rounded-full font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- EMAIL -->
                            <td class="p-4 text-gray-600">
                                {{ $user->email }}
                            </td>

                            <!-- RFID -->
                            <td class="p-4">
                                <span class="bg-gray-100 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ optional($user->rfidCards->first())->uid ?? '-' }}
                                </span>
                            </td>

                            <!-- ROLE -->
                            <td class="p-4">
                                @php $role = $user->role->name; @endphp

                                <span class="px-3 py-1 text-xs rounded-full font-semibold text-white
                                    {{ $role == 'admin' ? 'bg-blue-500' :
                                       ($role == 'petugas' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>

                            <!-- AKSI -->
                            <td class="p-4">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                                        ✏️
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs shadow"
                                            onclick="return confirm('Yakin hapus user?')">
                                            🗑
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-10">
                                <div class="flex flex-col items-center text-gray-400">
                                    <div class="text-4xl mb-2">📭</div>
                                    <p class="text-lg font-semibold">Belum ada user</p>
                                    <p class="text-sm">Silakan tambah user terlebih dahulu</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- FILTER SCRIPT -->
<script>
function filterRole(role) {
    let rows = document.querySelectorAll('.user-row');

    rows.forEach(row => {
        if (role === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.role === role ? '' : 'none';
        }
    });
}
</script>

@endsection