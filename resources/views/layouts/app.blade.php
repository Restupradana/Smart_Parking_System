<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- STYLE CUSTOM -->
    <style>
        .menu {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            transition: all 0.25s ease;
            position: relative;
        }

        .menu:hover {
            background: #eff6ff;
            transform: translateX(4px);
        }

        .active {
            background: linear-gradient(to right, #2563eb, #3b82f6);
            color: white !important;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .active::before {
            content: '';
            position: absolute;
            left: -10px;
            width: 5px;
            height: 60%;
            background: #1d4ed8;
            border-radius: 5px;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <!-- NAVBAR -->
    <nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center shadow">
        <h1 class="font-bold text-lg">🚗 Smart Parking System</h1>

        <!-- RIGHT -->
        <div class="flex items-center gap-4">

            <!-- USER INFO -->
            <div class="text-right hidden md:block">
                <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                <p class="text-xs text-green-500">{{ auth()->user()->email }}</p>
            </div>

            <!-- AVATAR -->
            <div class="w-10 h-10 bg-blue-500 text-white flex items-center justify-center rounded-full font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="flex">

        <!-- SIDEBAR -->
        <div class="flex">
            @include('layouts.sidebar')

            {{-- CONTENT --}}
            <main class="flex-1 p-6" data-parking-dashboard>
                @yield('content')
            </main>

            </div>

    </div>

</body>

</html>