<x-app-layout>
    <x-slot name="header">
        <div class="px-4 py-3 bg-indigo-600 text-white rounded-b-md shadow">
            <h2 class="font-semibold text-xl">
                <i class="bi bi-person-badge-fill me-2"></i> Pilih Role Anda
            </h2>
        </div>
    </x-slot>

    <div class="container py-5">
        <div class="card bg-light text-dark shadow-sm rounded-4">
            <div class="card-body">
                <h5 class="mb-4">Silakan pilih peran (role) yang ingin Anda gunakan untuk mengakses sistem:</h5>

                <form method="POST" action="{{ route('pilih.role.post') }}">
                    @csrf

                    @foreach ($roles as $role)
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="role" value="{{ $role }}"
                                id="role_{{ $role }}" required>
                            <label class="form-check-label" for="role_{{ $role }}">
                                {{ ucfirst($role) }}
                            </label>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-arrow-right-circle me-1"></i> Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>