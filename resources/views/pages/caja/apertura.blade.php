<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Control de Efectivo</h2>
                <p class="text-slate-400 font-medium mt-1">Inicia tu jornada laboral estableciendo el fondo de caja inicial.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-5 py-2.5 bg-white border border-slate-100 rounded-2xl shadow-sm ripple flex items-center gap-3">
                    <i class="fas fa-cash-register text-blue-500"></i>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Estación de Cobro</span>
                </div>
            </div>
        </div>

        @if ($cajaAbierta)
            <div class="max-w-3xl mx-auto animate-in zoom-in-95 duration-500">
                <div class="bg-blue-600 p-10 rounded-[3rem] text-white shadow-2xl shadow-blue-200 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="flex flex-col items-center text-center space-y-6">
                        <div class="w-20 h-20 rounded-[2rem] bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl">
                            <i class="fas fa-lock-open text-white"></i>
                        </div>
                        
                        <div>
                            <h3 class="text-3xl font-black tracking-tight italic">Caja Actualmente Abierta</h3>
                            <p class="text-blue-100 font-bold text-sm mt-2">Ya tienes una sesión activa en este equipo.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 w-full pt-6">
                            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-3xl text-left border border-white/10">
                                <p class="text-[9px] font-black text-blue-200 uppercase tracking-widest mb-1 italic">Monto de Inicio</p>
                                <p class="text-2xl font-black tracking-tighter">S/ {{ number_format($cajaAbierta->monto_apertura, 2) }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-3xl text-left border border-white/10">
                                <p class="text-[9px] font-black text-blue-200 uppercase tracking-widest mb-1 italic">Fecha Apertura</p>
                                <p class="text-sm font-black uppercase italic">{{ $cajaAbierta->fecha_apertura->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="pt-6">
                            <a href="{{ route('ventas.posventa.index') }}" 
                               class="inline-flex items-center gap-4 px-10 py-5 bg-white text-blue-600 rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs shadow-xl transition-all hover:scale-105 active:scale-95">
                                IR AL PUNTO DE VENTA <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Feedbacks -->
            @if (session('success') || session('error'))
                <div class="max-w-2xl mx-auto animate-in fade-in duration-500">
                    @if (session('success'))
                        <div class="p-6 bg-green-50 border border-green-100 rounded-[2rem] text-green-700 flex items-center gap-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                            <p class="font-bold italic">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="p-6 bg-red-50 border border-red-100 rounded-[2rem] text-red-700 flex items-center gap-4">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                            <p class="font-bold italic">{{ session('error') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Opening Action -->
            <div class="max-w-xl mx-auto pt-10">
                <div class="bg-white/70 backdrop-blur-xl border border-white p-12 rounded-[4rem] shadow-[0_50px_100px_rgba(0,0,0,0.06)] text-center relative overflow-hidden group">
                    <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="w-24 h-24 mx-auto mb-10 rounded-[2.5rem] bg-slate-50 flex items-center justify-center text-4xl text-slate-300 border border-slate-100 shadow-inner group-hover:text-blue-500 group-hover:bg-blue-50 transition-all duration-500">
                        <i class="fas fa-door-open"></i>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter italic mb-4">Iniciar Nueva Sesión</h3>
                    <p class="text-slate-400 font-bold text-sm mb-12 px-6">Para comenzar a registrar ventas, debes declarar el efectivo disponible en caja.</p>

                    <button id="openModalButton" 
                            class="w-full py-6 bg-blue-600 hover:bg-slate-950 text-white rounded-[2.5rem] font-black uppercase tracking-[0.25em] text-sm shadow-2xl shadow-blue-100 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-4">
                        <i class="fas fa-plus-circle text-xl"></i> ABRIR CAJA AHORA
                    </button>
                    
                    <p class="mt-8 text-[9px] font-black text-slate-300 uppercase tracking-widest italic tracking-[0.3em]">Seguridad POS Pro</p>
                </div>
            </div>

            <!-- Modal Apertura -->
            <div id="aperturaModal" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
                <div class="bg-white rounded-[3rem] p-12 w-full max-w-md shadow-[0_50px_100px_rgba(0,0,0,0.2)] relative animate-in zoom-in-95 duration-300">
                    <button id="closeModalButton" class="absolute top-10 right-10 w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic border-b border-slate-50 pb-6">Declaración de Fondos</h3>
                    
                    <form action="{{ route('caja.apertura') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block">Monto de Apertura (PEN)</label>
                            <div class="relative group">
                                <span class="absolute left-6 inset-y-0 flex items-center text-slate-300 font-black text-lg group-focus-within:text-blue-500 transition-colors">S/</span>
                                <input type="number" name="monto_apertura" step="0.01" placeholder="0.00" required
                                       class="w-full pl-14 pr-6 py-5 bg-slate-50 border-none rounded-3xl focus:ring-[10px] focus:ring-blue-500/5 text-2xl font-black text-slate-900 outline-none transition-all placeholder:text-slate-200">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full py-6 bg-blue-600 hover:bg-slate-950 text-white rounded-[2.5rem] font-black uppercase tracking-[0.2em] text-xs shadow-2xl transition-all active:scale-95">
                                Confirmar y Abrir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButton = document.getElementById('openModalButton');
            const aperturaModal = document.getElementById('aperturaModal');
            const closeModalButton = document.getElementById('closeModalButton');

            if(openModalButton) {
                openModalButton.addEventListener('click', function() {
                    aperturaModal.classList.remove('hidden');
                    aperturaModal.classList.add('flex');
                });
            }

            if(closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    aperturaModal.classList.add('hidden');
                    aperturaModal.classList.remove('flex');
                });
            }

            // Close on outside click
            if(aperturaModal) {
                aperturaModal.addEventListener('click', (e) => {
                    if(e.target === aperturaModal) {
                        aperturaModal.classList.add('hidden');
                        aperturaModal.classList.remove('flex');
                    }
                });
            }
        });
    </script>
</x-app-layout>