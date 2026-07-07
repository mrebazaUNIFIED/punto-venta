<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter italic uppercase">Archivo de
                    Transacciones</h2>
                <p class="text-slate-400 font-medium mt-1">Auditabilidad total de ventas realizadas en el salón y
                    terminales POS.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                    <i class="fas fa-shield-alt text-brand"></i>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Registro de
                        Auditoría Certificado</span>
                </div>
            </div>
        </div>

        <!-- Professional Filter Dashboard -->
        <div
            class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)]">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end text-left">
                <div class="md:col-span-3 space-y-3 text-left">
                    <label
                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Periodo
                        Inicial:</label>
                    <div class="relative group">
                        <i
                            class="fas fa-calendar-alt absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand transition-colors"></i>
                        <input type="date" id="start_date"
                            class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-8 focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all"
                            value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="md:col-span-3 space-y-3">
                    <label
                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Cierre
                        de Periodo:</label>
                    <div class="relative group">
                        <i
                            class="fas fa-calendar-check absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="date" id="end_date"
                            class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all"
                            value="{{ request('end_date', now()->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="md:col-span-3 space-y-3 text-left">
                    <label
                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Localizador
                        (Código):</label>
                    <div class="relative group">
                        <i
                            class="fas fa-barcode absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-brand transition-colors"></i>
                        <input type="text" id="codigo" placeholder="Ej: V-100234"
                            class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-8 focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all placeholder:text-slate-200"
                            value="{{ request('codigo') }}">
                    </div>
                </div>

                <div class="md:col-span-3">
                    <button id="filterButton"
                        class="w-full py-5 bg-slate-900 hover:bg-brand text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-sync-alt text-sm"></i> Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- High-Fidelity Audit Table -->
        <div
            class="bg-white/70 backdrop-blur-xl border border-white rounded-[4rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Transacción #</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Operador / Cajero</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                Importe Liquido</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                Metodología</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                Temporalidad</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">
                                Detalle</th>
                        </tr>
                    </thead>
                    <tbody id="ventasTableBody" class="divide-y divide-slate-50">
                        @forelse ($ventas as $index => $venta)
                            <tr class="group hover:bg-blue-50/30 transition-all duration-300">
                                <td class="px-8 py-8">
                                    <span
                                        class="text-xs font-black text-slate-900 tracking-tighter italic">{{ $venta->codigo }}</span>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-brand transition-colors">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 tracking-tight italic">
                                                {{ $venta->user->name ?? 'Sistema' }}</p>
                                            <p
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">
                                                Operador Verificado</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center text-sm font-black text-brand italic tracking-tighter">
                                    S/ {{ number_format($venta->total, 2) }}
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <span
                                        class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] bg-slate-50 text-slate-600 border border-slate-100 shadow-sm">
                                        <i class="fas fa-wallet mr-2 opacity-50"></i>
                                        {{ $venta->tipo_pago }}
                                    </span>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="text-xs font-black text-slate-400 uppercase tracking-widest italic">{{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }}</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-300 mt-1 italic">{{ \Carbon\Carbon::parse($venta->created_at)->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-right">
                                    <button
                                        class="view-detail group relative h-12 w-12 rounded-2xl bg-slate-100 hover:bg-slate-900 transition-all flex items-center justify-center border border-slate-200 active:scale-90"
                                        data-id="{{ $venta->detalleVentas->first()->id ?? '' }}">
                                        <i
                                            class="fas fa-receipt text-slate-400 group-hover:text-white transition-colors"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-24 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-20">
                                        <i class="fas fa-inbox text-6xl"></i>
                                        <p class="text-[11px] font-black uppercase tracking-widest italic">Archivo de salón
                                            sin registros</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ventas->hasPages())
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $ventas->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Premium Transaction Modal -->
        <div id="detailModal"
            class="fixed inset-0 bg-slate-950/60 backdrop-blur-md z-[110] hidden items-center justify-center p-4">
            <div
                class="bg-white rounded-[4rem] p-12 w-full max-w-2xl shadow-[0_100px_200px_rgba(0,0,0,0.3)] relative animate-in slide-in-from-bottom-20 duration-500 overflow-y-auto max-h-[90vh] custom-scrollbar">
                <button id="closeModal"
                    class="absolute top-10 right-10 w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <h3
                    class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-12 italic border-b border-slate-50 pb-8">
                    Ticket de Auditoría</h3>

                <div id="modalContent" class="space-y-10">
                    <!-- Contenido dinámico -->
                </div>

                <div class="mt-12 text-center">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em] italic mb-6">Operación
                        Validada por Protocolo POS Pro</p>
                    <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                        class="px-10 py-5 bg-slate-900 hover:bg-slate-800 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs transition-all active:scale-95">
                        Cerrar Visor
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function loadModalDetail(detalleId) {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = '<div class="py-20 text-center text-blue-500 animate-spin text-3xl"><i class="fas fa-circle-notch"></i></div>';

            fetch(`/ventas/detalles/${detalleId}`)
                .then(res => res.json())
                .then(data => {
                    modalContent.innerHTML = `
                        <!-- Voucher Header -->
                        <div class="flex flex-col items-center text-center space-y-4 mb-10">
                            <div class="w-20 h-20 rounded-[2.5rem] bg-blue-600 flex items-center justify-center shadow-2xl shadow-blue-100 text-white text-3xl">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div>
                                <p class="text-3xl font-black text-slate-950 tracking-tighter italic uppercase">${data.venta.codigo}</p>
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] italic mt-1">Transacción Certificada</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 shadow-inner">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Operador Responsable</p>
                                <p class="text-sm font-black text-slate-900 italic">${data.venta.user_nombre}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Fecha de Operación</p>
                                <p class="text-sm font-black text-slate-900 italic">${new Date(data.venta.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>

                        <!-- Itemized Table -->
                        <div class="bg-white rounded-[3rem] border border-slate-100 overflow-hidden shadow-sm">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-slate-50">
                                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Articulo</th>
                                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-center">Cant.</th>
                                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest italic text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        ${data.detalles.map(det => `
                                            <tr class="group hover:bg-slate-50/50">
                                                <td class="px-8 py-5 text-xs font-black text-slate-900 uppercase tracking-tight italic">${det.articulo_nombre}</td>
                                                <td class="px-8 py-5 text-center text-sm font-black text-slate-600 italic">${det.cantidad}</td>
                                                <td class="px-8 py-5 text-right text-sm font-black text-blue-600 italic">S/ ${parseFloat(det.subtotal).toFixed(2)}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="flex justify-end pr-8">
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] italic mb-2">Total Consolidado</p>
                                <p class="text-5xl font-black text-blue-600 tracking-tighter italic">S/ ${parseFloat(data.venta.total).toFixed(2)}</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('detailModal').classList.remove('hidden');
                    document.getElementById('detailModal').classList.add('flex');
                })
                .catch(err => {
                    modalContent.innerHTML = '<div class="py-20 text-center text-red-500 font-black italic">Error de sincronización con el servidor central</div>';
                });
        }

        document.getElementById('filterButton').addEventListener('click', function () {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const codigo = document.getElementById('codigo').value;
            window.location.href = `/ventas/detalles?start_date=${startDate}&end_date=${endDate}&codigo=${codigo}`;
        });

        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('ventasTableBody').addEventListener('click', (e) => {
                const btn = e.target.closest('.view-detail');
                if (btn) loadModalDetail(btn.getAttribute('data-id'));
            });
        });
    </script>
</x-app-layout>