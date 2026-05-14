<section class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
    <header class="mb-4">
        <h2 class="text-xl font-semibold text-primary dark:text-white">
            {{ __('Perbarui Kata Sandi') }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Gunakan kata sandi yang panjang dan acak untuk menjaga keamanan akun Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update.password') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Password Saat Ini --}}
        <div>
            <label for="update_password_current_password"
                class="form-label text-muted">{{ __('Kata Sandi Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="form-control bg-light @error('current_password', 'updatePassword') is-invalid @enderror"
                autocomplete="current-password" placeholder="••••••••">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div>
            <label for="update_password_password" class="form-label text-muted">{{ __('Kata Sandi Baru') }}</label>
            <input id="update_password_password" name="password" type="password"
                class="form-control bg-light @error('password', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password" placeholder="••••••••">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="update_password_password_confirmation"
                class="form-label text-muted">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control bg-light" autocomplete="new-password" placeholder="••••••••">
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-semibold">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-success small fw-semibold">
                    {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>