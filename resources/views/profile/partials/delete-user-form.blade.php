<section class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-md shadow-sm">
    <header>
        <h2 class="text-xl font-semibold text-danger">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua data akan hilang secara permanen. Harap unduh data yang ingin disimpan sebelum menghapus akun.') }}
        </p>
    </header>

    <button type="button"
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn btn-outline-danger d-flex align-items-center gap-2 rounded-pill px-4 py-2 fw-semibold shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ __('Hapus Akun') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
            @csrf
            @method('delete')

            <h2 class="text-lg fw-semibold text-danger">
                {{ __('Apakah Anda yakin ingin menghapus akun ini?') }}
            </h2>

            <p class="mt-2 text-sm text-muted">
                {{ __('Setelah dihapus, semua data akan hilang secara permanen. Masukkan kata sandi Anda untuk melanjutkan penghapusan akun.') }}
            </p>

            <div class="mt-4">
                <label for="password" class="form-label text-muted fw-semibold">{{ __('Kata Sandi') }}</label>
                <input id="password" name="password" type="password"
                       class="form-control bg-light @error('password', 'userDeletion') is-invalid @enderror"
                       placeholder="••••••••" required>
                @error('password', 'userDeletion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="button" x-on:click="$dispatch('close')"
                    class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                    {{ __('Batal') }}
                </button>

                <button type="submit"
                    class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="bi bi-trash-fill me-1"></i>
                    {{ __('Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
