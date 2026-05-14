@extends('layouts.app')

@section('title', 'Edit Landing Page')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                🖼 Kelola Landing Page
            </h2>
            <p class="text-gray-500 text-sm">
                Ubah tampilan halaman utama aplikasi Smart Parking
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
            class="text-sm bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg transition">
            ← Kembali
        </a>
    </div>

    <!-- CARD FORM -->
    <div class="bg-white rounded-2xl shadow-lg p-8">

        <form id="formUpdate"
            action="{{ route('admin.home.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">

            @csrf
            @method('PUT')

            <!-- JUDUL -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Judul Landing Page
                </label>
                <input type="text" name="title" value="{{ $section->title }}"
                    class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700">
                    Deskripsi
                </label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">{{ $section->description }}</textarea>
            </div>

            <!-- IMAGE SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- GAMBAR -->
                <div class="bg-gray-50 p-4 rounded-xl border">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Gambar Landing
                    </label>

                    <input type="file" name="image_path"
                        onchange="previewImage(event,'previewImage')"
                        class="mb-3 text-sm">

                    <div class="flex justify-center">
                        <img id="previewImage"
                            src="{{ $section->image_path ? asset('storage/' . $section->image_path) : '' }}"
                            class="w-40 h-28 object-cover rounded-lg shadow border">
                    </div>
                </div>

                <!-- LOGO -->
                <div class="bg-gray-50 p-4 rounded-xl border">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Logo
                    </label>

                    <input type="file" name="logo_path"
                        onchange="previewImage(event,'previewLogo')"
                        class="mb-3 text-sm">

                    <div class="flex justify-center">
                        <img id="previewLogo"
                            src="{{ $section->logo_path ? asset('storage/' . $section->logo_path) : '' }}"
                            class="w-20 h-20 object-cover rounded-full shadow border">
                    </div>
                </div>

            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-3 pt-4">

                <button type="reset"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg transition">
                    Reset
                </button>

                <button id="btnSubmit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition font-semibold">
                    💾 Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<!-- TOAST NOTIFICATION -->
@if(session('success'))
<div id="toast"
    class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg animate-bounce">
    ✔ Perubahan berhasil disimpan!
</div>

<script>
    setTimeout(() => {
        document.getElementById('toast').style.display = 'none';
    }, 2500);
</script>
@endif

<!-- SCRIPT -->
<script>
    function previewImage(event, id) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById(id).src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById("formUpdate").addEventListener("submit", function () {
        const btn = document.getElementById("btnSubmit");
        btn.innerHTML = "⏳ Menyimpan...";
        btn.classList.add("opacity-70");
        btn.disabled = true;
    });
</script>

@endsection