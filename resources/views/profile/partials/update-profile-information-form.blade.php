<section>
    <header class="mb-4">
        <h2 class="text-xl font-semibold text-primary">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="text-sm text-muted">
            {{ __("Perbarui informasi profil dan email akun Anda di bawah ini.") }}
        </p>
    </header>

    {{-- Form kirim ulang verifikasi --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Form update profile --}}
    <form method="post" action="{{ route('profile.update') }}" class="p-4 bg-white dark:bg-gray-800 border rounded-3 shadow-sm space-y-5">
        @csrf
        @method('patch')

        {{-- Nama --}}
        <div>
            <label for="name" class="form-label fw-semibold text-muted">{{ __('Nama') }}</label>
            <input type="text" id="name" name="name"
                   class="form-control bg-light @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="form-label fw-semibold text-muted">{{ __('Email') }}</label>
            <input type="email" id="email" name="email"
                   class="form-control bg-light @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" readonly/>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 alert alert-warning p-3 small rounded">
                    <p class="mb-1">
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                        <button type="submit" form="send-verification"
                                class="btn btn-link p-0 text-decoration-underline text-primary fw-semibold">
                            {{ __('Kirim ulang email verifikasi.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success mt-2">
                            {{ __('Link verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-success small fw-semibold">
                    {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>
