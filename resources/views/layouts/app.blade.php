<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS Pro') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-light: #eff6ff;
            --brand-glow: rgba(37, 99, 235, 0.1);
            --brand-slate: #0f172a;
        }

        .text-brand {
            color: var(--brand-primary);
        }

        .bg-brand {
            background-color: var(--brand-primary);
        }

        .border-brand {
            border-color: var(--brand-primary);
        }

        .hover\:text-brand:hover {
            color: var(--brand-primary);
        }

        .hover\:bg-brand:hover {
            background-color: var(--brand-primary);
        }
    </style>
</head>

<body class="font-sans antialiased bg-[#f8fafc] text-slate-900 selection:bg-blue-100 selection:text-blue-700">
    <div class="flex h-screen overflow-hidden shrink-0" x-data="{ 
            open: true, 
            dropdownOpen: false, 
            submenuOpen1: false, 
            submenuOpen2: false, 
            submenuOpen3: false, 
            submenuOpen4: false, 
            submenuOpen5: false, 
            submenuOpen6: false, 
            submenuOpen7: false 
        }">

        <!-- Sidebar -->
        @include('layouts.nav')

        <!-- Main Workspace -->
        <div
            class="flex-1 flex flex-col min-w-0 bg-white lg:rounded-l-[2.5rem] shadow-2xl shadow-slate-200/50 my-0 lg:my-3 mr-0 lg:mr-3 border border-slate-100 overflow-hidden relative transition-all duration-500">

            <!-- Dynamic Top Navigation -->
            <header
                class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-50 px-8 flex items-center justify-between sticky top-0 z-40">
                <div class="flex items-center gap-6">
                    <button @click="open = !open"
                        class="p-2.5 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-brand transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div class="h-8 w-[1px] bg-slate-100 hidden md:block"></div>
                    <nav
                        class="hidden md:flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-slate-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-brand transition-colors">Admin</a>
                        <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
                        <span class="text-slate-900">Dashboard</span>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dashboard Quick Stats or Actions -->


                    <!-- User Account Dropdown -->
                    <div class="relative ml-4">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center gap-3 p-1 pr-3 rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center border border-blue-200 overflow-hidden shadow-inner">
                                <img src="{{ asset('images/human.png') }}" alt="Avatar"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-xs font-black text-slate-950 uppercase leading-none mb-1">
                                    {{ auth()->user()->name }}
                                </p>
                                <p
                                    class="text-[10px] font-bold text-slate-400 tracking-tighter uppercase leading-none italic">
                                    Administrador</p>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-300 transition-transform duration-300"
                                :class="dropdownOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                            class="absolute right-0 mt-3 w-56 bg-white border border-slate-100 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-2 z-50">

                            <div class="px-4 py-4 border-b border-slate-50 mb-1">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 italic">
                                    Sesión iniciada</p>
                                <p class="text-[11px] font-bold text-slate-600 truncate">{{ auth()->user()->email }}</p>
                            </div>


                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-red-50 text-red-600 font-bold text-sm transition-all text-left">
                                    <i class="fas fa-power-off"></i> {{ __('Cerrar sesión') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-10 bg-slate-50/50">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Success & Error Notifications (Premium) -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: `{!! session('success') !!}`,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    iconColor: '#2563eb',
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Ha ocurrido un error',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    iconColor: '#ef4444',
                });
            });
        </script>
    @endif
</body>

</html>