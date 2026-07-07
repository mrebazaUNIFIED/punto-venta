<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Cierre de Operaciones</h2>
                <p class="text-slate-400 font-medium mt-1">Finaliza las sesiones de caja abiertas para consolidar el arqueo.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-5 py-2.5 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                    <i class="fas fa-lock text-red-500"></i>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-red-500/80">Sesiones Activas</span>
                </div>
            </div>
        </div>

        @if ($cajasAbiertas->isEmpty())
            <div class="max-w-xl mx-auto pt-10">
                <div class="bg-blue-50 border border-blue-100 p-10 rounded-[3rem] text-center relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/50 rounded-full blur-3xl"></div>
                    
                    <div class="w-20 h-20 mx-auto mb-6 rounded-3xl bg-white border border-blue-100 flex items-center justify-center text-3xl text-blue-200">
                        <i class="fas fa-check-double"></i>
                    </div>
                    
                    <h3 class="text-xl font-black text-blue-900 tracking-tight italic">Todo en orden</h3>
                    <p class="text-blue-400 font-bold text-sm mt-2 leading-relaxed px-6">
                        No hay cajas abiertas pendientes de cierre en este momento.
                    </p>
                </div>
            </div>
        @else
            <!-- Feedbacks -->
            @if (session('success') || session('error'))
                <div class="animate-in fade-in zoom-in-95 duration-500">
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

            <!-- Active Boxes Table -->
            <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-50">
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">ID</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Responsable / Ubicación</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Fondo Inicial</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Hora de Apertura</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">Acción de Cierre</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($cajasAbiertas as $index => $caja)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-8 text-center">
                                    <span class="text-xs font-black text-slate-300">#{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 shadow-inner">
                                            <i class="fas fa-user-clock"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 tracking-tight">{{ auth()->user()->name }}</p>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic mt-0.5">Terminal Central</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <span class="text-lg font-black text-slate-900 tracking-tighter">S/ {{ number_format($caja->monto_apertura, 2) }}</span>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $caja->fecha_apertura->format('d/m/Y') }}</span>
                                        <span class="text-sm font-black text-blue-600 mt-1">{{ $caja->fecha_apertura->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-right">
                                    <form action="{{ route('caja.update', $caja->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="px-6 py-3 bg-red-500 hover:bg-slate-950 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl shadow-red-100 transition-all hover:scale-105 active:scale-95 flex items-center gap-3">
                                            <i class="fas fa-power-off text-xs"></i> Cerrar Caja Pro
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="bg-red-50 p-8 rounded-[2.5rem] border border-red-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                    </div>
                    <p class="text-xs font-bold text-red-900 leading-relaxed italic">
                        Al cerrar la caja se consolidarán todas las ventas registradas hasta el momento. <br>
                        Asegúrate de haber realizado el arqueo físico del efectivo.
                    </p>
                </div>
                <div class="hidden md:block h-10 w-px bg-red-200/50"></div>
                <span class="text-[9px] font-black text-red-400 uppercase tracking-[0.4em] italic">Protocolo de Seguridad Activo</span>
            </div>
        @endif
    </div>
</x-app-layout>