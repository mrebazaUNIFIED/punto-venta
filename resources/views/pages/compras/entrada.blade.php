<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Entrada de productos</h2>

        <form action="{{ route('compras.entrada.store') }}" method="POST" id="entradaForm">
            @csrf
            <div class="flex flex-row gap-4">
                <!-- Card Izquierda: Detalles -->
                <div class="w-2/3 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <!-- Búsqueda de Artículos y Modo Scanner -->
                    <div class="mb-4">
                        <div class="flex items-center mt-2">
                            <div class="relative">
                                <input type="text" id="searchArticulo" placeholder="Buscar por nombre o código" class="border rounded p-2 w-[700px]" autocomplete="off">
                                <div id="articuloSuggestions" class="absolute left-0 right-0 border rounded mt-1 p-2 bg-white hidden z-10"></div>
                            </div>
                            <div class="flex items-center">
                                <span class="pl-5 text-sm font-medium text-gray-700">Modo Scanner</span>
                                <div class="relative inline-block w-12 h-6">
                                    <input type="checkbox" id="scannerToggle" class="absolute w-0 h-0 opacity-0" />
                                    <label for="scannerToggle" class="absolute cursor-pointer top-0 left-0 right-0 bottom-0 bg-gray-300 rounded-full transition-colors duration-300"></label>
                                    <span id="scannerKnob" class="absolute w-4 h-4 bg-white rounded-full transition-transform duration-300 transform translate-x-1 top-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos de entrada -->
                    <div class="grid grid-cols-4 gap-4 mb-4" id="inputFields">
                        <input type="number" name="detalles[0][cantidad]" placeholder="Cantidad" class="border rounded p-2" value="0" step="1" id="cantidad">
                        <input type="number" name="detalles[0][precio]" placeholder="P. compra" class="border rounded p-2" value="0.00" step="0.01" id="precio" disabled>
                        <input type="number" name="detalles[0][p_venta]" placeholder="P. venta" class="border rounded p-2" value="0.00" step="0.01" id="p_venta" disabled>
                        <input type="hidden" name="detalles[0][articulo_id]" id="articulo_id">
                        <button type="button" class="bg-green-500 text-white p-2 rounded" onclick="addRow()">Agregar</button>
                    </div>

                    <!-- Tabla -->
                    <div class="w-full mb-4 overflow-y-auto h-[500px] border">
                        <table class="w-full " id="detalleTable">
                            <thead>
                                <tr class="bg-gray-800 text-white sticky top-0">
                                    <th class="p-2 text-center">#</th>
                                    <th class="p-2 text-center">Código</th>
                                    <th class="p-2 text-center">Artículo</th>
                                    <th class="p-2 text-center">Cantidad</th>
                                    <th class="p-2 text-center">Precio compra</th>
                                    <th class="p-2 text-center">Precio venta</th>
                                    <th class="p-2 text-center">Subtotal</th>
                                    <th class="p-2 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="detalleBody" class="divide-y border">
                                <!-- Filas se agregarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                     <div class="text-right mb-4 flex items-center justify-end">
                        <p><B class="text-xl pr-3">Total S/ </B></p>
                        <div class="w-[150px] bg-gray-300 rounded-md text-left pl-3">
                            <p class="py-2 text-gray-600">
                                <span id="total">0.00</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card Derecha: Proveedor y Folio -->
                <div class="w-1/3 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Datos</h3>
                    <div class="mb-4">
                        <label for="proveedor_id" class="block text-sm font-medium text-gray-700">Buscar el proveedor</label>
                        <select name="proveedor_id" id="proveedor_id" class="border rounded p-2 w-full" required>
                            <option value="">Seleccione</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="folio" class="block text-sm font-medium text-gray-700">Folio</label>
                        <input type="text" name="folio" id="folio" class="border rounded p-2 w-full bg-gray-300" value="{{ $siguienteFolio }}" readonly required>
                    </div>

                    <div class="mb-4">
                        <label for="total_card" class="block text-sm font-medium text-gray-700">Total S/</label>
                        <input type="text" id="total_card" class="border rounded p-2 w-full" value="0.00" readonly>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white p-2 rounded w-full">Aceptar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let rowIndex = 0;
        let scannerMode = false;
        let lastScanTime = 0;

        // Toggle modo scanner con switch
        document.getElementById('scannerToggle').addEventListener('change', function() {
            scannerMode = this.checked;
            const switchElement = this.nextElementSibling; // El label actúa como pista
            const knobElement = document.getElementById('scannerKnob');
            if (scannerMode) {
                switchElement.classList.remove('bg-gray-300');
                switchElement.classList.add('bg-green-500');
                knobElement.classList.remove('translate-x-1');
                knobElement.classList.add('translate-x-7');
                document.getElementById('searchArticulo').focus();
                alert('Modo Scanner habilitado. Escanee un código de barras.');
            } else {
                switchElement.classList.remove('bg-green-500');
                switchElement.classList.add('bg-gray-300');
                knobElement.classList.remove('translate-x-7');
                knobElement.classList.add('translate-x-1');
            }
        });

        // Habilitar campos de precio y venta al seleccionar un artículo
        document.getElementById('searchArticulo').addEventListener('change', function() {
            if (this.value) {
              
            } else {
                document.getElementById('precio').value = '0.00';
                document.getElementById('p_venta').value = '0.00';
            }
        });

        // Búsqueda dinámica de artículos (manual o scanner)
        document.getElementById('searchArticulo').addEventListener('input', function(e) {
            const query = this.value.trim();
            const currentTime = new Date().getTime();

            // Detectar entrada rápida (típica de un scanner)
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

            // Búsqueda manual o sugerencias (funciona siempre)
            fetch(`/compras/search-articulos?query=${query}`)
                .then(response => response.json())
                .then(articulos => {
                    const suggestions = document.getElementById('articuloSuggestions');
                    suggestions.innerHTML = '';
                    articulos.forEach(articulo => {
                        const div = document.createElement('div');
                        div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                        div.innerText = `${articulo.codigo} - ${articulo.nombre} (Stock: ${articulo.inventario?.stock ?? 0})`;
                        div.onclick = () => {
                            document.getElementById('searchArticulo').value = articulo.nombre;
                            document.getElementById('articulo_id').value = articulo.id;
                            document.getElementById('precio').value = articulo.p_compra;
                            document.getElementById('p_venta').value = articulo.p_venta;
                            document.getElementById('cantidad').value = 1;
                            suggestions.classList.add('hidden');
                        };
                        suggestions.appendChild(div);
                    });
                    suggestions.classList.remove('hidden');
                });
        });

        // Manejar escaneo
        function handleScan(code) {
            fetch(`/compras/search-articulos?query=${code}`)
                .then(response => response.json())
                .then(articulos => {
                    if (articulos.length > 0) {
                        const articulo = articulos[0]; // Tomar el primer resultado
                        document.getElementById('searchArticulo').value = articulo.nombre;
                        document.getElementById('articulo_id').value = articulo.id;
                        document.getElementById('precio').value = articulo.p_compra;
                        document.getElementById('p_venta').value = articulo.p_venta;
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('precio').removeAttribute('disabled');
                        document.getElementById('p_venta').removeAttribute('disabled');
                        addRow(); // Agregar directamente a la tabla
                    } else {
                        alert('Código de barras no encontrado.');
                    }
                })
                .catch(error => {
                    console.error('Error al buscar artículo:', error);
                    alert('Error al procesar el escaneo.');
                });
        }

        // Agregar fila a la tabla
        function addRow() {
            const articuloId = document.getElementById('articulo_id').value;
            const articuloNombre = document.getElementById('searchArticulo').value;
            const cantidad = parseInt(document.getElementById('cantidad').value) || 0;
            const precio = parseFloat(document.getElementById('precio').value) || 0.00;
            const pVenta = parseFloat(document.getElementById('p_venta').value) || 0.00;

            if (!articuloId || cantidad <= 0 || precio <= 0) {
                alert('Por favor, seleccione un artículo y complete los campos.');
                return;
            }

            rowIndex++;
            const subtotal = (cantidad * precio).toFixed(2);
            const newRow = `
                <tr class="border-t" data-articulo-id="${articuloId}">
                    <td class="p-2 text-center">${rowIndex}</td>
                    <td class="p-2 text-center">001</td> <!-- Esto debería ser dinámico, ver nota -->
                    <td class="p-2 text-center">${articuloNombre}</td>
                    <td class="p-2 text-center">${cantidad}</td>
                    <td class="p-2 text-center">${precio.toFixed(2)}</td>
                    <td class="p-2 text-center">${pVenta.toFixed(2)}</td>
                    <td class="p-2 text-center">${subtotal}</td>
                    <td class="p-2 text-center"><button class="text-red-500" onclick="removeRow(this)">✕</button></td>
                </tr>`;
            document.getElementById('detalleBody').insertAdjacentHTML('beforeend', newRow);
            updateTotal();

            // Limpiar campos
            if (!scannerMode) {
                document.getElementById('articulo_id').value = '';
                document.getElementById('searchArticulo').value = '';
            }
            document.getElementById('cantidad').value = 0;
            document.getElementById('precio').setAttribute('disabled', true);
            document.getElementById('p_venta').setAttribute('disabled', true);
            document.getElementById('precio').value = '0.00';
            document.getElementById('p_venta').value = '0.00';
        }

        function removeRow(button) {
            button.closest('tr').remove();
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#detalleBody tr').forEach(row => {
                const subtotal = parseFloat(row.cells[6].textContent) || 0;
                total += subtotal;
            });
            document.getElementById('total').textContent = total.toFixed(2);
            document.getElementById('total_card').value = total.toFixed(2);
        }

        // Enviar datos de la tabla al formulario antes de submit
        document.getElementById('entradaForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevenir envío inmediato

            const detalles = [];
            document.querySelectorAll('#detalleBody tr').forEach(row => {
                const articuloId = row.getAttribute('data-articulo-id');
                const articuloNombre = row.cells[2].textContent;
                const cantidad = parseInt(row.cells[3].textContent);
                const precio = parseFloat(row.cells[4].textContent);
                const pVenta = parseFloat(row.cells[5].textContent);

                detalles.push({
                    articulo_id: articuloId,
                    cantidad: cantidad,
                    precio: precio,
                    p_venta: pVenta
                });

                // Agregar campo oculto para cada detalle
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `detalles[${detalles.length - 1}][articulo_id]`;
                input.value = articuloId;
                this.appendChild(input);

                const inputCantidad = document.createElement('input');
                inputCantidad.type = 'hidden';
                inputCantidad.name = `detalles[${detalles.length - 1}][cantidad]`;
                inputCantidad.value = cantidad;
                this.appendChild(inputCantidad);

                const inputPrecio = document.createElement('input');
                inputPrecio.type = 'hidden';
                inputPrecio.name = `detalles[${detalles.length - 1}][precio]`;
                inputPrecio.value = precio;
                this.appendChild(inputPrecio);

                const inputPVenta = document.createElement('input');
                inputPVenta.type = 'hidden';
                inputPVenta.name = `detalles[${detalles.length - 1}][p_venta]`;
                inputPVenta.value = pVenta;
                this.appendChild(inputPVenta);
            });

            // Validación
            if (detalles.length === 0) {
                alert('Debe agregar al menos un artículo.');
                return;
            }

            // Enviar el formulario
            this.submit();
        });

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener('click', function(e) {
            const suggestions = document.getElementById('articuloSuggestions');
            if (!suggestions.contains(e.target) && e.target.id !== 'searchArticulo') {
                suggestions.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>