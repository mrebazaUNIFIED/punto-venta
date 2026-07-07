<x-app-layout>
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black text-slate-950 tracking-tighter italic">Recepción de Mercancía</h2>
                <p class="text-slate-400 font-medium mt-1">Registra el ingreso de nuevos artículos al inventario central.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 px-5 py-2.5 bg-green-50 border border-green-100 rounded-2xl shadow-sm">
                    <div class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-green-600 uppercase tracking-widest italic">Terminal de Abastecimiento</span>
                </div>
            </div>
        </div>

        <form action="{{ route('compras.entrada.store') }}" method="POST" id="entradaForm">
            @csrf
            <div class="flex flex-col xl:flex-row gap-8">
                
                <!-- Left Column: Product Selection & Table -->
                <div class="flex-grow space-y-8">
                    
                    <div class="bg-white/70 backdrop-blur-xl border border-white p-8 rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)]">
                        <!-- Advanced Controls Strip -->
                        <div class="flex flex-col md:flex-row gap-6 mb-8 items-center">
                            <div class="relative flex-grow group">
                                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                </div>
                                <input type="text" id="searchArticulo" placeholder="Escribe el nombre del producto o escanea código..." 
                                       class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-[2rem] focus:ring-[10px] focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all placeholder:text-slate-300" autocomplete="off">
                                
                                <!-- Search Results Dropdown -->
                                <div id="articuloSuggestions" 
                                     class="absolute left-0 right-0 top-full mt-4 bg-white/95 backdrop-blur-md rounded-[2.5rem] shadow-[0_50px_100px_rgba(0,0,0,0.15)] border border-white overflow-hidden hidden z-[100] animate-in fade-in slide-in-from-top-4 duration-300">
                                </div>
                            </div>

                            <div class="shrink-0 flex items-center gap-4 bg-slate-50 px-8 py-3 rounded-[2rem] border border-slate-100">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Scanner Pro</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="scannerToggle" class="sr-only peer">
                                    <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-1 after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Manual Entry Inputs -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end" id="inputFields">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block">Cantidad Recibida</label>
                                <input type="number" id="cantidad" placeholder="0" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-8 focus:ring-blue-500/5 text-slate-900 font-bold outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block">P. Compra Unitario</label>
                                <div class="relative group">
                                    <span class="absolute left-4 inset-y-0 flex items-center text-slate-300 font-black text-xs">S/</span>
                                    <input type="number" id="precio" step="0.01" placeholder="0.00" disabled class="w-full pl-10 pr-4 py-4 bg-slate-100 border-none rounded-2xl text-slate-400 font-bold outline-none cursor-not-allowed">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block">Ajuste P. Venta</label>
                                <div class="relative group">
                                    <span class="absolute left-4 inset-y-0 flex items-center text-slate-300 font-black text-xs">S/</span>
                                    <input type="number" id="p_venta" step="0.01" placeholder="0.00" disabled class="w-full pl-10 pr-4 py-4 bg-slate-100 border-none rounded-2xl text-slate-400 font-bold outline-none cursor-not-allowed">
                                </div>
                            </div>
                            <input type="hidden" id="articulo_id">
                            <button type="button" onclick="addRow()" 
                                    class="py-4 bg-slate-900 hover:bg-slate-950 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl shadow-slate-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <i class="fas fa-plus-circle text-sm"></i> AGREGAR
                            </button>
                        </div>
                    </div>

                    <!-- Details Table -->
                    <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[3rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] italic">Pre-visualización de Carga</h3>
                            <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-3 py-1 rounded-full uppercase tracking-tighter border border-blue-100 italic">Válido para entrada</span>
                        </div>
                        
                        <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse" id="detalleTable">
                                <thead class="sticky top-0 bg-white/95 backdrop-blur-md z-10">
                                    <tr class="border-b border-slate-100">
                                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">#</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Articulo</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Cant.</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-center">Subtotal Compra</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] italic text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="detalleBody" class="divide-y divide-slate-50">
                                    <!-- Dynamic rows go here -->
                                </tbody>
                            </table>
                            
                            <!-- Empty State Table -->
                            <div id="emptyTableState" class="py-20 text-center opacity-30">
                                <i class="fas fa-cloud-upload-alt text-5xl mb-6"></i>
                                <p class="text-[11px] font-black uppercase tracking-widest italic">Aún no has agregado artículos a la entrada</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Metadata & Sums -->
                <div class="xl:w-[400px] shrink-0 space-y-8">
                    
                    <div class="sticky top-8 space-y-8">
                        <!-- Registry Metadata Card -->
                        <div class="bg-blue-600 p-10 rounded-[3.5rem] shadow-2xl shadow-blue-100 flex flex-col space-y-8 relative overflow-hidden group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
                            
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-xl">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h3 class="text-xl font-black text-white italic tracking-tight">Registro de Ingreso</h3>
                            </div>

                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-blue-200 uppercase tracking-[0.2em] ml-1 italic block">Folio de Registro (Auto)</label>
                                    <input type="text" name="folio" value="{{ $siguienteFolio }}" readonly
                                           class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-2xl text-white font-black text-lg focus:ring-0 outline-none">
                                </div>

                                <div class="space-y-2 text-left">
                                    <label class="text-[9px] font-black text-blue-200 uppercase tracking-[0.2em] ml-1 italic block text-left">Socio Comercial (Proveedor)</label>
                                    <select name="proveedor_id" id="proveedor_id" required
                                            class="w-full px-6 py-5 bg-white/10 border border-white/20 rounded-2xl text-white font-bold appearance-none outline-none focus:bg-white/20 transition-all cursor-pointer">
                                        <option value="" class="text-slate-900 font-bold">Seleccionar Empresa...</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}" class="text-slate-900 font-bold">{{ strtoupper($proveedor->nombre) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Summary & Trigger Card -->
                        <div class="bg-white/70 backdrop-blur-xl border border-white p-10 rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] flex flex-col space-y-8">
                            <div class="flex items-center justify-between">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] italic">Resumen de Carga</h4>
                                <i class="fas fa-receipt text-slate-200 text-xl"></i>
                            </div>

                            <div class="space-y-4 pt-4">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 italic">
                                    <span>Subtotal estimado</span>
                                    <span id="subtotalDisplay">S/ 0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 italic border-b border-slate-50 pb-4">
                                    <span>Impuestos aplicados</span>
                                    <span>S/ 0.00</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-tighter">Total Inversión</span>
                                    <span class="text-3xl font-black text-blue-600 tracking-tighter italic">S/ <span id="totalDisplay">0.00</span></span>
                                </div>
                            </div>

                            <input type="hidden" name="total" id="totalInput" value="0.00">

                            <div class="pt-6">
                                <button type="submit" 
                                        class="w-full py-6 bg-slate-950 hover:bg-blue-600 text-white rounded-[2.5rem] font-black uppercase tracking-[0.25em] text-sm shadow-2xl transition-all hover:scale-[1.05] active:scale-95 flex items-center justify-center gap-4">
                                    PROCESAR ENTRADA <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <script>
        let rowIndex = 0;
        let scannerMode = false;
        let lastScanTime = 0;

        // Toggle UI logic
        document.getElementById('scannerToggle').addEventListener('change', function() {
            scannerMode = this.checked;
            if(scannerMode) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Modo Scanner Activo',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: { popup: 'rounded-2xl font-bold' }
                });
                document.getElementById('searchArticulo').focus();
            }
        });

        // Search Engine
        const searchInput = document.getElementById('searchArticulo');
        const suggestionsBox = document.getElementById('articuloSuggestions');

        searchInput.addEventListener('input', function(e) {
            const query = this.value.trim();
            const currentTime = new Date().getTime();

            // Detect lightning-fast input (Scanner)
            if (scannerMode && currentTime - lastScanTime < 100 && e.inputType === 'insertText') {
                handleScan(query);
                this.value = '';
                return;
            }
            lastScanTime = currentTime;

            if (query.length < 2) {
                suggestionsBox.classList.add('hidden');
                return;
            }

            fetch(`/compras/search-articulos?query=${query}`)
                .then(res => res.json())
                .then(articulos => {
                    suggestionsBox.innerHTML = '';
                    if (articulos.length === 0) {
                        suggestionsBox.innerHTML = '<div class="p-6 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center italic">Sin coincidencias en catálogo</div>';
                    } else {
                        articulos.forEach(art => {
                            const div = document.createElement('div');
                            div.className = 'group p-6 hover:bg-blue-50 cursor-pointer flex items-center justify-between border-b border-slate-50 last:border-0 transition-colors duration-300';
                            div.innerHTML = `
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 tracking-tight italic uppercase">${art.nombre}</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-0.5">Cod: ${art.codigo} | Stock: ${art.inventario?.stock ?? 0}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black text-blue-600 italic">S/ ${parseFloat(art.p_compra).toFixed(2)}</p>
                                    <p class="text-[8px] font-black text-slate-300 uppercase">Sug: S/ ${parseFloat(art.p_venta).toFixed(2)}</p>
                                </div>
                            `;
                            div.onclick = () => selectArticulo(art);
                            suggestionsBox.appendChild(div);
                        });
                    }
                    suggestionsBox.classList.remove('hidden');
                });
        });

        function selectArticulo(art) {
            document.getElementById('searchArticulo').value = art.nombre;
            document.getElementById('articulo_id').value = art.id;
            
            const pCompra = document.getElementById('precio');
            const pVenta = document.getElementById('p_venta');
            
            pCompra.value = art.p_compra;
            pCompra.disabled = false;
            pCompra.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            pCompra.classList.add('bg-slate-50', 'text-slate-900');
            
            pVenta.value = art.p_venta;
            pVenta.disabled = false;
            pVenta.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            pVenta.classList.add('bg-slate-50', 'text-slate-900');
            
            document.getElementById('cantidad').value = 1;
            document.getElementById('cantidad').focus();
            suggestionsBox.classList.add('hidden');
        }

        function handleScan(code) {
            fetch(`/compras/search-articulos?query=${code}`)
                .then(res => res.json())
                .then(articulos => {
                    if (articulos.length > 0) {
                        selectArticulo(articulos[0]);
                        setTimeout(addRow, 100);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Código no ubicado',
                            text: 'El artículo escaneado no está en el catálogo.',
                            customClass: { popup: 'rounded-[3rem]' }
                        });
                    }
                });
        }

        function addRow() {
            const artId = document.getElementById('articulo_id').value;
            const artNombre = document.getElementById('searchArticulo').value;
            const cant = parseInt(document.getElementById('cantidad').value);
            const pC = parseFloat(document.getElementById('precio').value);
            const pV = parseFloat(document.getElementById('p_venta').value);

            if (!artId || isNaN(cant) || cant <= 0) {
                Swal.fire({ icon: 'error', title: 'Datos faltantes', text: 'Verifique artículo y cantidad', customClass: { popup: 'rounded-[3rem]' } });
                return;
            }

            rowIndex++;
            const sub = (cant * pC).toFixed(2);
            const row = document.createElement('tr');
            row.className = 'group hover:bg-blue-50/30 transition-colors animate-in zoom-in-95 duration-300';
            row.setAttribute('data-articulo-id', artId);
            row.innerHTML = `
                <td class="px-8 py-5 text-center text-xs font-black text-slate-300 italic">#${str_pad(rowIndex, 2)}</td>
                <td class="px-8 py-5">
                    <div>
                        <p class="text-[11px] font-black text-slate-900 uppercase tracking-tight">${artNombre}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase italic">Venta: S/ ${pV.toFixed(2)}</p>
                    </div>
                </td>
                <td class="px-8 py-5 text-center text-sm font-black text-slate-900 italic">${cant}</td>
                <td class="px-8 py-5 text-center text-sm font-black text-blue-600 italic">S/ ${sub}</td>
                <td class="px-8 py-5 text-right">
                    <button type="button" onclick="removeRow(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center mx-auto">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <input type="hidden" name="detalles[${rowIndex-1}][articulo_id]" value="${artId}">
                    <input type="hidden" name="detalles[${rowIndex-1}][cantidad]" value="${cant}">
                    <input type="hidden" name="detalles[${rowIndex-1}][precio]" value="${pC}">
                    <input type="hidden" name="detalles[${rowIndex-1}][p_venta]" value="${pV}">
                </td>
            `;
            document.getElementById('detalleBody').appendChild(row);
            document.getElementById('emptyTableState').classList.add('hidden');
            updateTotal();
            resetInputs();
        }

        function resetInputs() {
            document.getElementById('articulo_id').value = '';
            document.getElementById('searchArticulo').value = '';
            document.getElementById('cantidad').value = '';
            
            const pc = document.getElementById('precio');
            const pv = document.getElementById('p_venta');
            
            pc.value = ''; pc.disabled = true; pc.classList.replace('bg-slate-50', 'bg-slate-100');
            pv.value = ''; pv.disabled = true; pv.classList.replace('bg-slate-50', 'bg-slate-100');
            
            setTimeout(() => document.getElementById('searchArticulo').focus(), 100);
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            updateTotal();
            if(document.querySelectorAll('#detalleBody tr').length === 0) {
                document.getElementById('emptyTableState').classList.remove('hidden');
            }
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#detalleBody tr').forEach(row => {
                const sub = parseFloat(row.cells[3].textContent.replace('S/ ', ''));
                total += sub;
            });
            document.getElementById('totalDisplay').textContent = total.toFixed(2);
            document.getElementById('subtotalDisplay').textContent = 'S/ ' + total.toFixed(2);
            document.getElementById('totalInput').value = total.toFixed(2);
        }

        function str_pad(n, width) {
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
        }

        // Outside clicks
        document.addEventListener('click', (e) => {
            if(!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                suggestionsBox.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>