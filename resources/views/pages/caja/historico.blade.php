<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Histórico de Movimientos</h2>
                <p class="text-slate-400 font-medium mt-1">Consulta y audita los cierres de caja anteriores del sistema.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-blue-500"></i>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">{{ now()->format('F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Advanced Filter Dashboard -->
        <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)]">
            <h3 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-8 italic flex items-center gap-3">
                <i class="fas fa-filter text-lg"></i> Filtros de Auditoría
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end">
                <div class="md:col-span-4 space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block">Rango: Fecha Inicio</label>
                    <div class="relative group">
                        <i class="fas fa-calendar-day absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="date" id="start_date" name="start_date"
                               class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all"
                               value="{{ old('start_date', $startDate ?? now()->subDays(30)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="md:col-span-4 space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block">Rango: Fecha Fin</label>
                    <div class="relative group">
                        <i class="fas fa-calendar-check absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="date" id="end_date" name="end_date"
                               class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all"
                               value="{{ old('end_date', $endDate ?? now()->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="md:col-span-4 translate-y-[-2px]">
                    <button id="filterButton" 
                            class="w-full py-5 bg-slate-900 hover:bg-blue-600 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-search-dollar text-sm"></i> Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>

        @if ($historico->isEmpty())
            <div class="py-20 text-center opacity-30 animate-in fade-in duration-1000">
                <i class="fas fa-folder-open text-6xl mb-6"></i>
                <p class="text-sm font-black uppercase tracking-widest italic">Archivo de cajas vacío para este periodo</p>
            </div>
        @else
            <!-- Transaction Table Card -->
            <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-50">
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">#</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Responsable</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Operación</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Estatus Pro</th>
                                <th class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($historico as $index => $caja)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-8">
                                    <span class="text-xs font-black text-slate-300">#{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-white flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 tracking-tight">{{ $caja['usuario_nombre'] }}</p>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5 italic">Usuario Autorizado</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center text-xs font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($caja['fecha_apertura'])->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-8 py-8 text-center">
                                    @php $isCerrada = $caja['estado'] === 'Cerrada'; @endphp
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] {{ $isCerrada ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-blue-50 text-blue-600 border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.2)]' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isCerrada ? 'bg-green-500' : 'bg-blue-500 animate-pulse' }} mr-2"></span>
                                        {{ $caja['estado'] }}
                                    </span>
                                </td>
                                <td class="px-8 py-8 text-right">
                                    <button class="view-detail group relative h-12 w-12 rounded-2xl bg-blue-600 hover:bg-slate-950 transition-all flex items-center justify-center shadow-lg shadow-blue-100 border border-white/20 active:scale-90"
                                            data-id="{{ $caja['id'] }}">
                                        <i class="fas fa-eye text-white group-hover:scale-110 transition-transform"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Premium Audit Modal -->
        <div id="detailModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4">
            <div class="bg-white rounded-[4rem] p-12 w-full max-w-xl shadow-[0_100px_200px_rgba(0,0,0,0.3)] relative animate-in slide-in-from-bottom-20 duration-500">
                <button id="closeModalButton" class="absolute top-10 right-10 w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-xl"></i>
                </button>
                
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic border-b border-slate-50 pb-8">Reporte Detallado de Arqueo</h3>
                
                <div class="flex items-center gap-6 mb-12">
                    <div class="w-20 h-20 rounded-[2.5rem] bg-blue-600 flex items-center justify-center shadow-2xl shadow-blue-200">
                        <i class="fas fa-signature text-3xl text-white"></i>
                    </div>
                    <div>
                        <p id="usuarioNombre" class="text-2xl font-black text-slate-950 tracking-tighter">---</p>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic mt-1">Responsable del Turno</p>
                    </div>
                </div>

                <div class="bg-slate-50/70 p-10 rounded-[3rem] border border-slate-100 shadow-inner overflow-hidden relative">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl"></div>
                    
                    <div id="modalContent" class="space-y-6">
                        <!-- Content Dynamic -->
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] italic mb-6">Auditoría Digital Certificada</p>
                    <button onclick="document.getElementById('detailModal').classList.add('hidden')" 
                            class="px-10 py-5 bg-slate-900 hover:bg-slate-800 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs transition-all active:scale-95">
                        Cerrar Auditoría
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('filterButton').addEventListener('click', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            window.location.href = `/caja/historico?start_date=${startDate}&end_date=${endDate}`;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('detailModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const modalContent = document.getElementById('modalContent');
            const usuarioNombre = document.getElementById('usuarioNombre');

            function createRow(label, value, extraClass = '') {
                return `
                    <div class="flex items-center justify-between py-1 border-b border-white/30 last:border-0">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">${label}</span>
                        <span class="text-sm font-black text-slate-900 tracking-tighter ${extraClass}">${value}</span>
                    </div>
                `;
            }

            document.querySelectorAll('.view-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const cajaId = this.getAttribute('data-id');
                    fetch(`/caja/${cajaId}`)
                        .then(res => res.json())
                        .then(data => {
                            usuarioNombre.textContent = data.usuario_nombre;
                            const faltante = data.monto_cierre && (data.monto_cierre < data.monto_apertura) ? data.monto_apertura - data.monto_cierre : 0;
                            const sobrante = data.monto_cierre && (data.monto_cierre > data.monto_apertura) ? data.monto_cierre - data.monto_apertura : 0;

                            modalContent.innerHTML = `
                                ${createRow('Operación', new Date(data.fecha_apertura).toLocaleString())}
                                ${createRow('Fondo Inicial', 'S/ ' + number_format(data.monto_apertura, 2))}
                                ${createRow('Efectivo Real', 'S/ ' + (data.monto_cierre ? number_format(data.monto_cierre, 2) : '0.00'))}
                                <div class="h-4"></div>
                                ${createRow('Ventas Efectivo', 'S/ ' + number_format(data.ventas_efectivo, 2), 'text-blue-600')}
                                ${createRow('Ventas Digitales', 'S/ ' + number_format(data.ventas_yape, 2), 'text-blue-500')}
                                <div class="h-4"></div>
                                ${createRow('Balance Faltante', 'S/ ' + number_format(faltante, 2), faltante > 0 ? 'text-red-500' : 'text-slate-300')}
                                ${createRow('Balance Sobrante', 'S/ ' + number_format(sobrante, 2), sobrante > 0 ? 'text-green-500' : 'text-slate-300')}
                            `;
                            detailModal.classList.remove('hidden');
                            detailModal.classList.add('flex');
                        });
                });
            });

            closeModalButton.addEventListener('click', () => {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            });
        });

        function number_format(number, decimals) {
            return number.toFixed(decimals).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    </script>
</x-app-layout>