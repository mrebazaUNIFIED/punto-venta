<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Control de Existencias</h2>
                <p class="text-slate-400 font-medium mt-1">Monitorea y ajusta los niveles de stock de tu catálogo
                    global.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('almacen.inventario.excel') }}"
                    class="group flex items-center gap-3 px-6 py-4 bg-white border border-slate-100 text-slate-600 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-sm hover:bg-green-50 hover:text-green-600 hover:border-green-100 transition-all">
                    <i class="fas fa-file-excel text-lg text-green-500 group-hover:scale-110 transition-transform"></i>
                    Exportar Excel
                </a>
                <a href="{{ route('almacen.inventario.pdf') }}"
                    class="group flex items-center gap-3 px-6 py-4 bg-white border border-slate-100 text-slate-600 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-sm hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all">
                    <i class="fas fa-file-pdf text-lg text-red-500 group-hover:scale-110 transition-transform"></i>
                    Generar PDF
                </a>
            </div>
        </div>

        <!-- Search & Global Stats Strip -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-5 relative group">
                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                    <i class="fas fa-barcode text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                </div>
                <input type="text" id="search" placeholder="Buscar por código o nombre de artículo..."
                    class="w-full pl-14 pr-6 py-4 bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] focus:ring-[8px] focus:ring-blue-500/5 text-slate-900 font-bold transition-all outline-none placeholder:text-slate-300 shadow-sm">
            </div>

            <div class="md:col-span-7 flex flex-wrap gap-4 justify-end">
                <div class="px-6 py-4 bg-blue-50/50 rounded-2xl border border-blue-100/50 flex items-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest italic">Sincronización
                        en Tiempo Real Activa</span>
                </div>
            </div>
        </div>

        <!-- Inventory Table Card -->
        <div
            class="bg-white/70 backdrop-blur-xl border border-white rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                Visual</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Código / SKU</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">
                                Artículo de Inventario</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                P. Compra</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">
                                P. Venta</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center w-40">
                                Existencia</th>
                            <th
                                class="px-8 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">
                                Ajuste</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-50 text-slate-600">
                        @foreach($inventario as $item)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        @if($item->articulo->imagen)
                                            <div
                                                class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-md ring-4 ring-slate-50 group-hover:ring-blue-50 transition-all">
                                                <img src="{{ asset('storage/articulos/' . $item->articulo->imagen) }}"
                                                    alt="Producto" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div
                                                class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                                                <i class="fas fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="text-xs font-black text-slate-900 mono tracking-tighter">{{ $item->articulo->codigo }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div>
                                        <p class="text-sm font-black text-slate-900 tracking-tight">
                                            {{ $item->articulo->nombre }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">
                                            {{ $item->articulo->categoria->nombre ?? 'Sin Categoría' }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-xs font-bold text-slate-400 italic">S/
                                        {{ number_format($item->articulo->p_compra, 2) }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-xs font-black text-blue-600">S/
                                        {{ number_format($item->articulo->p_venta, 2) }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="relative inline-flex items-center group/stock">
                                        <input type="number" id="stock-{{ $item->id }}"
                                            class="w-24 px-4 py-3 bg-white border-2 border-slate-100 rounded-2xl text-slate-900 font-black text-center focus:ring-[10px] focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all shadow-inner"
                                            value="{{ $item->stock }}" data-id="{{ $item->id }}">

                                        @if($item->stock <= 5)
                                            <div class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[8px] font-black shadow-lg shadow-red-200 animate-bounce cursor-help"
                                                title="Stock Crítico">
                                                <i class="fas fa-exclamation"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button onclick="actualizarStock('{{ $item->id }}')"
                                            class="h-11 px-6 bg-slate-900 hover:bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[9px] transition-all active:scale-95 shadow-xl shadow-slate-100 flex items-center gap-2">
                                            <i class="fas fa-sync-alt text-[10px]"></i> Sincronizar
                                        </button>
                                        <span id="loader-{{ $item->id }}"
                                            class="hidden text-blue-500 animate-spin text-sm">
                                            <i class="fas fa-circle-notch"></i>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($inventario->hasPages())
                <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $inventario->links() }}
                </div>
            @endif
        </div>

        <div
            class="bg-blue-600 p-8 rounded-[2.5rem] text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl shadow-blue-100 transition-transform hover:scale-[1.01] duration-500">
            <div class="flex items-center gap-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h4 class="text-lg font-black tracking-tight italic">Optimización de Almacén</h4>
                    <p class="text-blue-100 font-bold text-xs mt-1">El sistema sugiere reabastecimiento automático
                        cuando el stock es menor a 5 unidades.</p>
                </div>
            </div>
            <div class="hidden md:block w-px h-12 bg-white/20"></div>
            <p class="text-[9px] font-black uppercase tracking-[0.4em] opacity-60 italic">Gestión Logística certificada
            </p>
        </div>

    </div>

    <script>
        function actualizarStock(id) {
            const input = document.getElementById('stock-' + id);
            const loader = document.getElementById('loader-' + id);
            const stock = input.value;

            // Visual feedback
            loader.classList.remove('hidden');
            input.disabled = true;
            input.classList.add('opacity-50');

            fetch(`/almacen/inventario/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    stock: stock
                })
            })
                .then(res => {
                    if (!res.ok) throw new Error('Error de servidor');
                    return res.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Inventario Actualizado',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            popup: 'rounded-[3rem] p-10',
                            title: 'font-black text-2xl tracking-tighter',
                            content: 'font-bold text-slate-500 italic'
                        }
                    });

                    input.classList.remove('border-slate-100');
                    input.classList.add('border-green-500');
                    setTimeout(() => {
                        input.classList.remove('border-green-500');
                        input.classList.add('border-slate-100');
                    }, 3000);
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Crítico',
                        text: 'No se pudo sincronizar el stock. Intenta de nuevo.',
                        customClass: { popup: 'rounded-[3rem]' }
                    });
                    input.classList.add('border-red-500');
                })
                .finally(() => {
                    loader.classList.add('hidden');
                    input.disabled = false;
                    input.classList.remove('opacity-50');
                });
        }

        // Search engine optimization
        document.getElementById('search').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(term)) {
                    row.classList.remove('hidden');
                    row.classList.add('animate-in', 'fade-in', 'duration-300');
                } else {
                    row.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>