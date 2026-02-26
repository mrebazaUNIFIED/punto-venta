<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nombre Empresa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen" x-data="{ open: true, dropdownOpen: false, submenuOpen1: false, submenuOpen2: false, submenuOpen3: false, submenuOpen4: false, submenuOpen5: false, submenuOpen6: false, submenuOpen7: false }">
        @include('layouts.nav')
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header with Toggle Button and Avatar -->
            <header class="bg-white border-b border-gray-100 p-4 flex justify-between items-center">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>

                <div class="text-sm text-gray-600">
                    <a href={{route('dashboard')}} class="inline-block font-bold text-xl text-gray-800 bg-gray-100 hover:bg-gray-900 hover:text-white px-4 py-3 rounded-lg shadow-sm hover:shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out">
                        Dashboard
                    </a>
                </div>

                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
                        <img src="{{ asset('images/human.png') }}" alt="Avatar" class="w-8 h-8 rounded-full">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                        <!--<a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                        -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cerrar sesión</a>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                position: "center-center",
                icon: 'success',
                title: '¡Éxito!',
                html: `{!! session('success') !!}`, 
                showConfirmButton: false,
                timer: 1500,
                iconColor: '#16a34a'
            });
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: '¡Algo salió mal!',
                text: '{{ session('
                error ') }}',
                showConfirmButton: false,
                timer: 1500,
                iconColor: '#ef4444'
            });
        });
    </script>
    @endif

</body>

</html>