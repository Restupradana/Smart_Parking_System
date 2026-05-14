<nav class="bg-green-600 text-white p-4 flex justify-between items-center shadow">

    <h1 class="text-xl font-bold">
       🚗 Smart Parking - Admin Panel
    </h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
            Logout
        </button>
    </form>

</nav>