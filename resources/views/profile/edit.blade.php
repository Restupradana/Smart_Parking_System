<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 bg-gradient text-white rounded-bottom"
             style="background: linear-gradient(to right, #4e73df, #1cc88a);">
            <h2 class="fw-semibold fs-4 mb-0">
                <i class="bi bi-person-circle me-2"></i> {{ __('Profil Pengguna') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-5 bg-light min-vh-100">
        <div class="container">
            <div class="row justify-content-center g-4">

                <!-- Informasi Profil -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="card-title text-primary mb-3">
                                <i class="bi bi-pencil-square me-2"></i> Ubah Informasi Profil
                            </h5>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Ubah Password -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="card-title text-warning mb-3">
                                <i class="bi bi-lock-fill me-2"></i> Ganti Password
                            </h5>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Hapus Akun -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="card-title text-danger mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Hapus Akun
                            </h5>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
        <style>
            body {
                background-color: #f8f9fa;
            }
            .card:hover {
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                transform: translateY(-2px);
                transition: all 0.3s ease-in-out;
            }
        </style>
    @endpush
</x-app-layout>
