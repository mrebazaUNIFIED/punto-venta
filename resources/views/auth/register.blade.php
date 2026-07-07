<x-guest-layout>
    <div class="relative z-10 w-full uppercase">
        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 mb-6 mx-auto">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500">Registro de Personal</span>
            </div>
            <h2 class="text-6xl font-black text-slate-950 tracking-tighter mb-4 animate-in fade-in slide-in-from-bottom-4 duration-700">Registro</h2>
            <p class="text-slate-500 font-semibold text-lg leading-tight animate-in fade-in slide-in-from-bottom-4 duration-700 delay-100 italic">Alta de nuevos colaboradores</p>
        </div>

        <div class="bg-white/50 backdrop-blur-sm border border-slate-100 rounded-[2.5rem] p-1 shadow-2xl shadow-slate-200/50 animate-in zoom-in-95 duration-500 delay-200">
            <div class="bg-white rounded-[2.2rem] p-8 md:p-10 border border-slate-50">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Name -->
                        <div class="group relative">
                            <x-input-label for="name" :value="__('Nombre Completo')" class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] mb-3 px-1 group-focus-within:text-blue-600 transition-colors duration-300" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-blue-500 transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <x-text-input id="name"
                                    class="block w-full pl-14 pr-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                                    placeholder="Tu Empresa o Nombre" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider" />
                        </div>

                        <!-- Username (Optional) -->
                        <div class="group relative">
                            <x-input-label for="username" :value="__('Nombre de Usuario (Opcional)')"
                                class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] mb-3 px-1 group-focus-within:text-blue-600 transition-colors duration-300" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-blue-500 transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </span>
                                <x-text-input id="username"
                                    class="block w-full pl-14 pr-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="text" name="username" :value="old('username')" autocomplete="username"
                                    placeholder="usuario_pos" />
                            </div>
                            <x-input-error :messages="$errors->get('username')" class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider" />
                        </div>

                        <!-- Email Address -->
                        <div class="group relative">
                            <x-input-label for="email" :value="__('Correo Electrónico')" class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] mb-3 px-1 group-focus-within:text-blue-600 transition-colors duration-300" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-blue-500 transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <x-text-input id="email"
                                    class="block w-full pl-14 pr-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="email" name="email" :value="old('email')" required autocomplete="username"
                                    placeholder="hola@tunegocio.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider" />
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="group relative">
                                <x-input-label for="password" :value="__('Contraseña')" class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] mb-3 px-1 group-focus-within:text-blue-600 transition-colors duration-300" />
                                <x-text-input id="password"
                                    class="block w-full px-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider" />
                            </div>

                            <div class="group relative">
                                <x-input-label for="password_confirmation" :value="__('Confirmar')" class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] mb-3 px-1 group-focus-within:text-blue-600 transition-colors duration-300" />
                                <x-text-input id="password_confirmation"
                                    class="block w-full px-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="password" name="password_confirmation" required autocomplete="new-password"
                                    placeholder="••••••••" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-12">
                        <x-primary-button
                            class="w-full justify-center bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-2xl py-5 text-sm font-black uppercase tracking-[0.25em] shadow-2xl shadow-blue-500/40 transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
                            {{ __('Crear mi cuenta') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-12 text-center border-t border-slate-50 pt-10">
                        <p class="text-sm text-slate-400 font-bold tracking-tight">
                            ¿YA TIENES CUENTA?
                            <a href="{{ route('login') }}"
                                class="ml-2 text-blue-600 hover:text-blue-800 border-b-2 border-blue-600/10 hover:border-blue-600 transition-all pb-1 uppercase text-xs tracking-widest">
                                {{ __('Inicia sesión') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>