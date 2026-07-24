<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $section->title ?? 'Smart Parking System' }}</title>

    {{-- ✅ Dynamic Favicon --}}
    @if (isset($section) && $section->logo_path)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $section->logo_path) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Instrument Sans"', 'sans-serif'],
                    },
                    colors: {
                        trashGreen: '#22c55e',
                        trashDark: '#16a34a',
                        bgDark: '#0a0a0a',
                        bgLight: '#FDFDFC',
                    },
                },
            },
        };
    </script>
</head>

<body
    class="bg-bgLight dark:bg-bgDark text-[#1b1b18] dark:text-[#EDEDEC] font-sans min-h-screen flex flex-col items-center justify-center p-6 lg:p-8 transition-colors duration-300">

    {{-- HEADER NAVIGATION --}}
    <header class="w-full lg:max-w-4xl max-w-[335px] mb-6 text-sm">
        @if (Route::has('login'))
            <nav
                class="flex items-center justify-end gap-4 px-4 py-2 bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm dark:shadow-lg transition-all duration-300">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 rounded-lg text-sm font-medium text-white bg-trashGreen hover:bg-trashDark transition duration-200 shadow-md">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 rounded-lg text-sm font-medium text-trashGreen border border-trashGreen hover:bg-green-50 dark:text-green-300 dark:hover:bg-green-800 transition duration-200">
                        Log in dulu dong
                    </a>

                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-5 py-2 rounded-lg text-sm font-medium text-trashGreen border border-trashGreen hover:bg-green-50 dark:text-green-300 dark:hover:bg-green-800 transition duration-200">
                            Register
                        </a>
                    @endif --}}
                @endauth
            </nav>
        @endif
    </header>

    {{-- ILLUSTRATION + TEXT SECTION --}}
    <main class="text-center max-w-2xl">
        @if (isset($section) && $section)
            <img src="{{ asset('storage/' . $section->image_path) }}" alt="Foto Smart Parking System"
                class="w-full max-w-4xl mx-auto mb-6 rounded-xl shadow-lg object-cover h-48 sm:h-64 md:h-80 transition duration-300 dark:brightness-90" />

            <h1 class="text-3xl lg:text-4xl font-bold text-trashGreen dark:text-green-400 mb-4">
                {{ $section->title }}
            </h1>

            <p class="text-base lg:text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ $section->description }}
            </p>
        @else
            <p class="text-gray-400">Konten belum tersedia.</p>
        @endif
    </main>

    {{-- OPTIONAL SPACER --}}
    @if (Route::has('login'))
        <div class="h-14 hidden lg:block"></div>
    @endif

</body>

</html>
