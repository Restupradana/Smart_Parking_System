@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">👤 Tambah User</h2>
        <p class="text-gray-500 text-sm">Tambahkan user baru dan scan kartu RFID</p>
    </div>

    <!-- ERROR -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM -->
    <div class="bg-white p-8 rounded-2xl shadow-lg">

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- NAMA -->
            <input type="text" name="name" placeholder="Nama"
                class="w-full border p-3 rounded-lg" required>

            <!-- EMAIL -->
            <input type="email" name="email" placeholder="Email"
                class="w-full border p-3 rounded-lg" required>

            <!-- RFID -->
            <div>
                <div class="flex gap-2">
                    <input type="text" id="rfid_tag" name="rfid_tag"
                        class="w-full border p-3 rounded-lg bg-gray-100"
                        placeholder="Scan kartu RFID..." readonly required>

                    <button type="button" onclick="startScan()" id="btnScan"
                        class="bg-blue-500 text-white px-4 rounded-lg">
                        Scan
                    </button>
                </div>

                <p id="scan-status" class="text-xs text-gray-500 mt-2">
                    Klik scan lalu tempel kartu
                </p>
            </div>

            <!-- ROLE -->
            <select name="role_id" class="w-full border p-3 rounded-lg">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>

            <!-- BUTTON -->
                <div class="flex justify-between items-center pt-4">

                    <a href="{{ route('admin.users.index') }}"
                        class="text-gray-500 hover:text-gray-700 text-sm">
                        ← Kembali
                    </a>

                    <button id="btnSubmit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg shadow font-semibold transition">
                        💾 Simpan User
                    </button>

                </div>

        </form>

    </div>

</div>

<script>
let scanning = false;
let interval = null;

function startScan() {

    if (scanning) return;

    scanning = true;

    const status = document.getElementById('scan-status');
    const btn = document.getElementById('btnScan');

    btn.innerText = "Scanning...";
    btn.disabled = true;
    status.innerText = "⏳ Menunggu kartu RFID...";

    let timeout = 0;

    interval = setInterval(() => {

        fetch('/api/v1/rfid/latest')
            .then(res => res.json())
            .then(data => {

                if (data.rfid_tag) {

                    document.getElementById('rfid_tag').value = data.rfid_tag;

                    status.innerText = "✅ RFID terbaca: " + data.rfid_tag;
                    btn.innerText = "Scan Lagi";
                    btn.disabled = false;

                    clearInterval(interval);
                    scanning = false;
                }
            });

        timeout++;

        // 🔥 STOP kalau 15 detik
        if (timeout > 7) {
            clearInterval(interval);
            scanning = false;
            btn.disabled = false;
            btn.innerText = "Scan";
            status.innerText = "❌ Tidak ada kartu terdeteksi";
        }

    }, 2000);
}
</script>

@endsection