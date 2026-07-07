<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="relative z-10 w-full animate-in fade-in zoom-in-95 duration-1000">
        <div class="mb-12 text-center">

            <h2 class="text-7xl font-black text-slate-950 tracking-tighter mb-4 uppercase">Login</h2>
            <p class="text-slate-400 font-bold text-lg tracking-tight italic">Panel Administrativo & Ventas</p>
        </div>

        <div
            class="bg-white/20 backdrop-blur-sm border border-slate-100 rounded-[2.5rem] p-1 shadow-2xl shadow-slate-200/50 animate-in zoom-in-95 duration-500 delay-200">
            <div class="bg-white rounded-[2.2rem] p-8 md:p-10 border border-slate-50">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-7">
                        <!-- Email Address -->
                        <div class="group relative">
                            <x-input-label for="email" :value="__('Correo Electrónico')"
                                class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] mb-3 px-1 group-focus-within:text-blue-600 transition-colors duration-300" />
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-blue-500 transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <x-text-input id="email"
                                    class="block w-full pl-14 pr-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="email" name="email" :value="old('email')" required autofocus
                                    autocomplete="username" placeholder="hola@tunegocio.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')"
                                class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider animate-in fade-in duration-300" />
                        </div>

                        <!-- Password -->
                        <div class="group relative">
                            <div class="flex items-center justify-between px-1 mb-3">
                                <x-input-label for="password" :value="__('Contraseña')"
                                    class="block text-slate-400 font-bold uppercase text-[11px] tracking-[0.2em] group-focus-within:text-blue-600 transition-colors duration-300" />
                            </div>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-blue-500 transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <x-text-input id="password"
                                    class="block w-full pl-14 pr-6 py-5 border-slate-100 bg-slate-50/50 focus:bg-white focus:border-blue-500 focus:ring-[8px] focus:ring-blue-500/15 focus:outline-none rounded-2xl transition-all duration-500 text-slate-900 font-medium placeholder:text-slate-300"
                                    type="password" name="password" required autocomplete="current-password"
                                    placeholder="••••••••" />
                            </div>
                            <x-input-error :messages="$errors->get('password')"
                                class="mt-2 text-[11px] font-bold text-red-500 uppercase tracking-wider animate-in fade-in duration-300" />
                        </div>
                    </div>

                    <div class="flex items-center mt-8 px-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none group">
                            <input id="remember_me" type="checkbox"
                                class="rounded-lg border-slate-200 text-blue-600 focus:ring-4 focus:ring-blue-500/10 w-6 h-6 transition-all cursor-pointer"
                                name="remember">
                            <span
                                class="ml-3 text-sm text-slate-500 font-semibold group-hover:text-slate-900 transition-colors">{{ __('Mantener sesión iniciada') }}</span>
                        </label>
                    </div>

                    <div class="mt-10">
                        <button type="submit"
                            class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                            {{ __('Entrar al Sistema') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>