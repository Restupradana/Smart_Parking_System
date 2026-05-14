<aside class="w-64 bg-white min-h-screen p-6 shadow-md">

    <h2 class="font-semibold text-gray-700 text-lg mb-6">
        Menu {{ ucfirst(auth()->user()->role->name ?? 'User') }}
    </h2>

    <ul class="space-y-2 text-gray-600 text-sm">

        {{-- ================= ADMIN ================= --}}
        @if(auth()->user()->role->name == 'admin')

            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="menu {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    👤 Kelola User
                </a>
            </li>

            <li>
                <a href="{{ route('admin.parking-areas.index') }}"
                    class="menu {{ request()->routeIs('admin.parking-areas.*') ? 'active' : '' }}">
                    🅿️ Area Parkir
                </a>
            </li>

            <li>
                <a href="{{ route('admin.parking-slots.index') }}"
                    class="menu {{ request()->routeIs('admin.parking-slots.*') ? 'active' : '' }}">
                    🚗 Slot Parkir
                </a>
            </li>

            <li>
                <a href="{{ route('admin.history') }}"
                    class="menu {{ request()->routeIs('admin.history') ? 'active' : '' }}">
                    📡 History RFID
                </a>
            </li>

            <li>
                <a href="{{ route('admin.home.edit') }}"
                    class="menu {{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
                    🖼 Landing Page
                </a>
            </li>

        @endif


        {{-- ================= PETUGAS ================= --}}
        @if(auth()->user()->role->name == 'petugas')

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
                <a href="{{ route('petugas.history') }}"
                    class="menu {{ request()->routeIs('petugas.history') ? 'active' : '' }}">
                    📜 History RFID
                </a>
            </li>

            <li>
                <a href="{{ route('petugas.gate') }}" class="menu {{ request()->routeIs('petugas.gate') ? 'active' : '' }}">
                    🚪 Gate
                </a>
            </li>

            <li>
                <a href="{{ route('petugas.password') }}"
                    class="menu {{ request()->routeIs('petugas.password') ? 'active' : '' }}">
                    🔐 Ganti Password
                </a>
            </li>

        @endif


        {{-- ================= USER ================= --}}
        @if(auth()->user()->role->name == 'pengguna')

            <li>
                <a href="{{ route('pengguna.dashboard') }}"
                    class="menu {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
                    👤 Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('pengguna.history') }}"
                    class="menu {{ request()->routeIs('pengguna.history') ? 'active' : '' }}">
                    📜 History
                </a>
            </li>

            <li>
                <a href="{{ route('pengguna.password') }}"
                    class="menu {{ request()->routeIs('pengguna.password') ? 'active' : '' }}">
                    🔐 Ganti Password
                </a>
            </li>

        @endif

    </ul>

</aside>