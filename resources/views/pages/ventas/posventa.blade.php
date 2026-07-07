<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter">Punto de Venta</h2>
                <p class="text-slate-400 font-medium mt-1">Gestión de ventas en tiempo real.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-5 py-2.5 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Sesión de Caja Activa</span>
                </div>
            </div>
        </div>

        <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm" class="relative">
            @csrf
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
                <!-- Main Workspace: Product Entry & Table (Left) -->
                <div class="xl:col-span-8 space-y-6">
                    
                    <!-- Search & Scanner Card -->
                    <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="relative flex-grow group">
                                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" id="searchArticulo" placeholder="Buscar por nombre o escanea código..." 
                                    class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-[8px] focus:ring-blue-500/10 text-slate-900 font-bold placeholder:text-slate-300 transition-all outline-none" 
                                    autocomplete="off">
                                <div id="articuloSuggestions" class="absolute left-0 right-0 mt-3 bg-white border border-slate-100 rounded-3xl shadow-2xl p-3 hidden z-50 animate-in fade-in zoom-in-95 duration-200 overflow-hidden"></div>
                            </div>

                            <div class="flex items-center gap-4 px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100">
                                <i class="fas fa-barcode text-slate-400"></i>
                                <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Scanner</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="scannerToggle" class="sr-only peer">
                                    <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 transition-colors"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Manual Adjustment Bar -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-slate-50">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Cantidad</label>
                                <input type="number" id="cantidad" value="1" step="1" 
                                    class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-xl focus:ring-[6px] focus:ring-blue-500/5 text-slate-900 font-black text-center text-lg outline-none cursor-pointer">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Precio Ref.</label>
                                <div class="relative">
                                    <span class="absolute left-4 inset-y-0 flex items-center text-slate-400 font-bold">S/</span>
                                    <input type="number" id="p_venta" value="0.00" step="0.01" disabled 
                                        class="w-full pl-10 pr-5 py-3.5 bg-slate-100 border-none rounded-xl text-slate-500 font-black text-lg outline-none">
                                </div>
                            </div>
                            <div class="md:col-span-2 flex items-end">
                                <button type="button" onclick="addRow()" 
                                    class="w-full py-4 bg-slate-900 hover:bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg hover:shadow-blue-500/20 active:scale-95 flex items-center justify-center gap-3">
                                    <i class="fas fa-plus-circle text-lg"></i> Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table Card -->
                    <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[2.5rem] shadow-sm overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                            <h3 class="text-sm font-black text-slate-950 uppercase tracking-widest italic">Detalle de Compra</h3>
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tabular-nums" id="rowCount">0 Items</span>
                        </div>
                        <div class="overflow-x-auto max-h-[450px] custom-scrollbar">
                            <table class="w-full text-left" id="detalleTable">
                                <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] sticky top-0 backdrop-blur-md">
                                    <tr>
                                        <th class="px-8 py-5">N°</th>
                                        <th class="px-8 py-5">Artículo</th>
                                        <th class="px-8 py-5 text-center">Cant.</th>
                                        <th class="px-8 py-5 text-right">Precio</th>
                                        <th class="px-8 py-5 text-right">Subtotal</th>
                                        <th class="px-8 py-5 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="detalleBody" class="divide-y divide-slate-50 font-bold text-slate-700">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Empty State -->
                        <div id="emptyState" class="py-20 flex flex-col items-center justify-center text-slate-200">
                            <i class="fas fa-shopping-basket text-6xl mb-4 opacity-10"></i>
                            <p class="text-sm font-black uppercase tracking-widest opacity-20">Carrito Vacío</p>
                        </div>
                    </div>
                </div>

                <!-- Action Sidebar: Totals & Checkout (Right) -->
                <div class="xl:col-span-4 sticky top-28 space-y-6">
                    <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.06)] relative overflow-hidden">
                        <!-- Decoration -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>
                        
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-8 italic">Resumen de Venta</h3>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Método de Pago</label>
                                <select name="tipo_pago" id="tipo_pago" required
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold outline-none focus:ring-[6px] focus:ring-blue-500/5 appearance-none">
                                    <option value="efectivo">💵 Efectivo / Cash</option>
                                    <option value="yape">📱 Yape / Plin</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Referencia / Código</label>
                                <input name="codigo" type="text" id="codigo" value="{{ $codigo }}" readonly
                                    class="w-full px-5 py-4 bg-slate-100 border-none rounded-2xl text-slate-500 font-black tabular-nums outline-none">
                            </div>

                            <div class="pt-6 mt-6 border-t border-slate-100 space-y-4">
                                <div class="flex items-center justify-between px-2">
                                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Subtotal</span>
                                    <span class="text-sm font-black text-slate-900 tabular-nums">S/ <span id="total_sub">0.00</span></span>
                                </div>
                                <div class="flex items-center justify-between px-2">
                                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Impuestos (0%)</span>
                                    <span class="text-sm font-black text-slate-900 tabular-nums">S/ 0.00</span>
                                </div>
                                
                                <div class="p-6 bg-slate-950 rounded-3xl mt-4 relative overflow-hidden group">
                                    <div class="absolute inset-0 bg-linear-to-tr from-blue-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.3em] mb-1 relative z-10 italic">Total a Cobrar</p>
                                    <div class="text-4xl font-black text-white tabular-nums relative z-10 flex items-center gap-2">
                                        <span class="text-lg opacity-50 font-medium">S/</span>
                                        <span id="total_display">0.00</span>
                                    </div>
                                    <input type="hidden" name="total" id="total_card" value="0.00">
                                </div>
                            </div>

                            <button type="submit" 
                                class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-sm shadow-2xl shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-check-circle text-lg"></i> Confirmar Venta
                            </button>
                        </div>
                    </div>

                    <!-- Quick Legend Card -->
                    <div class="bg-blue-600 p-6 rounded-[2rem] text-white/90">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-lightbulb text-blue-300"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest italic">Pro Tip</span>
                        </div>
                        <p class="text-[11px] font-bold leading-relaxed italic opacity-80">
                            Usa el <span class="bg-white/20 px-1.5 py-0.5 rounded">F2</span> para buscar rápidamente o <span class="bg-white/20 px-1.5 py-0.5 rounded">Enter</span> para confirmar.
                        </p>
                    </div>
                </div>
            </div>
            <input type="hidden" name="articulo_id" id="articulo_id">
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
    </style>

    <script>
        let rowIndex = 0;
        let scannerMode = false;
        let lastScanTime = 0;

        // Toggle modo scanner
        document.getElementById('scannerToggle').addEventListener('change', function() {
            scannerMode = this.checked;
            if (scannerMode) document.getElementById('searchArticulo').focus();
        });

        // Búsqueda dinámica
        document.getElementById('searchArticulo').addEventListener('input', function(e) {
            const query = this.value.trim();
            const currentTime = new Date().getTime();

            if (scannerMode && currentTime - lastScanTime < 100 && e.inputType === 'insertText') {
                handleScan(query);
                this.value = '';
                return;
            }
            lastScanTime = currentTime;

            if (query.length < 1) {
                document.getElementById('articuloSuggestions').classList.add('hidden');
                return;
            }

            fetch(`/compras/search-articulos?query=${query}`)
                .then(response => response.json())
                .then(articulos => {
                    const suggestions = document.getElementById('articuloSuggestions');
                    suggestions.innerHTML = '';
                    if(articulos.length > 0) {
                        articulos.forEach(articulo => {
                            const div = document.createElement('div');
                            div.className = 'p-4 hover:bg-blue-50 rounded-2xl cursor-pointer flex items-center justify-between group transition-all';
                            div.innerHTML = `
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">${articulo.codigo}</span>
                                    <span class="text-sm font-black text-slate-900 uppercase tracking-tight">${articulo.nombre}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-black text-blue-600 tabular-nums">S/ ${parseFloat(articulo.p_venta).toFixed(2)}</span>
                                    <div class="text-[9px] font-bold text-slate-300 uppercase italic">Stock: ${articulo.inventario?.stock ?? 0}</div>
                                </div>
                            `;
                            div.onclick = () => {
                                selectArticulo(articulo);
                                suggestions.classList.add('hidden');
                            };
                            suggestions.appendChild(div);
                        });
                        suggestions.classList.remove('hidden');
                    }
                });
        });

        function selectArticulo(articulo) {
            document.getElementById('searchArticulo').value = articulo.nombre;
            document.getElementById('articulo_id').value = articulo.id;
            document.getElementById('p_venta').value = articulo.p_venta;
            document.getElementById('cantidad').value = 1;
            document.getElementById('cantidad').focus();
        }

        function handleScan(code) {
            fetch(`/compras/search-articulos?query=${code}`)
                .then(response => response.json())
                .then(articulos => {
                    if (articulos.length > 0) {
                        selectArticulo(articulos[0]);
                        addRow();
                    }
                });
        }

        function addRow() {
            const articuloId = document.getElementById('articulo_id').value;
            const articuloNombre = document.getElementById('searchArticulo').value;
            let cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            const precioUnitario = parseFloat(document.getElementById('p_venta').value) || 0.00;

            if (!articuloId || cantidad <= 0 || precioUnitario <= 0) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'warning', title: 'Selecciona un artículo', showConfirmButton: false, timer: 2000
                });
                return;
            }

            let row = document.querySelector(`#detalleBody tr[data-articulo-id="${articuloId}"]`);
            if (row) {
                let currentCantidad = parseInt(row.cells[2].textContent);
                cantidad += currentCantidad;
                let newSubtotal = (cantidad * precioUnitario).toFixed(2);
                row.cells[2].textContent = cantidad;
                row.cells[4].textContent = 'S/ ' + newSubtotal;
            } else {
                rowIndex++;
                document.getElementById('emptyState').classList.add('hidden');
                const subtotal = (cantidad * precioUnitario).toFixed(2);
                const newRow = `
                    <tr class="hover:bg-slate-50 transition-colors group animate-in slide-in-from-right-4 duration-300" data-articulo-id="${articuloId}">
                        <td class="px-8 py-5 text-xs text-slate-300 italic tabular-nums">${rowIndex}</td>
                        <td class="px-8 py-5">
                            <span class="block text-sm font-black text-slate-900 uppercase tracking-tight">${articuloNombre}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase italic">Cod: ${articuloId}</span>
                        </td>
                        <td class="px-8 py-5 text-center font-black tabular-nums">${cantidad}</td>
                        <td class="px-8 py-5 text-right font-black tabular-nums text-slate-400">S/ ${precioUnitario.toFixed(2)}</td>
                        <td class="px-8 py-5 text-right font-black tabular-nums text-slate-950">S/ ${subtotal}</td>
                        <td class="px-8 py-5 text-center">
                            <button type="button" onclick="removeRow(this)" class="p-2 text-slate-200 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`;
                document.getElementById('detalleBody').insertAdjacentHTML('beforeend', newRow);
            }

            updateTotal();
            resetInputs();
        }

        function resetInputs() {
            if (!scannerMode) {
                document.getElementById('articulo_id').value = '';
                document.getElementById('searchArticulo').value = '';
                document.getElementById('searchArticulo').focus();
            }
            document.getElementById('cantidad').value = 1;
            document.getElementById('p_venta').value = '0.00';
        }

        function removeRow(button) {
            button.closest('tr').remove();
            updateTotal();
            if (document.querySelectorAll('#detalleBody tr').length === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
            }
            reindexRows();
        }

        function reindexRows() {
            rowIndex = 0;
            document.querySelectorAll('#detalleBody tr').forEach((row, index) => {
                rowIndex = index + 1;
                row.cells[0].textContent = rowIndex;
            });
        }

        function updateTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#detalleBody tr');
            rows.forEach(row => {
                const subtext = row.cells[4].textContent.replace('S/ ', '');
                total += parseFloat(subtext) || 0;
            });
            document.getElementById('total_sub').textContent = total.toFixed(2);
            document.getElementById('total_display').textContent = total.toFixed(2);
            document.getElementById('total_card').value = total.toFixed(2);
            document.getElementById('rowCount').textContent = `${rows.length} Items`;
        }

        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const detalles = [];
            document.querySelectorAll('#detalleBody tr').forEach((row, index) => {
                const articuloId = row.getAttribute('data-articulo-id');
                const cantidad = parseInt(row.cells[2].textContent);
                const precio = parseFloat(row.cells[3].textContent.replace('S/ ', ''));

                const fields = ['articulo_id', 'cantidad', 'p_venta'];
                const values = [articuloId, cantidad, precio];
                
                fields.forEach((field, fIndex) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `detalles[${index}][${field}]`;
                    input.value = values[fIndex];
                    this.appendChild(input);
                });
            });

            if (document.querySelectorAll('#detalleBody tr').length === 0) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: '¡El carrito está vacío!' });
                return;
            }

            this.submit();
        });
    </script>
</x-app-layout>