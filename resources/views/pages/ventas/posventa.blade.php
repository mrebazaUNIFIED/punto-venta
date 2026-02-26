<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Punto de Venta</h2>

        <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
            @csrf
            <div class="flex flex-row gap-4">
                <!-- Card Izquierda: Detalles -->
                <div class="w-2/3 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <!-- Búsqueda de Artículos y Modo Scanner -->
                    <div class="mb-4">
                        <div class="flex items-center mt-2">
                            <div class="relative">
                                <input type="text" id="searchArticulo" placeholder="Buscar por nombre o código de barras" class="border rounded p-2 w-[700px]" autocomplete="off">
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
                    <div class="grid grid-cols-3 gap-4 mb-4" id="inputFields">
                        <input type="number" name="detalles[0][cantidad]" placeholder="Cantidad" class="border rounded p-2" value="1" step="1" id="cantidad" >
                        <input type="number" name="detalles[0][p_venta]" placeholder="P. venta" class="border rounded p-2" value="0.00" step="0.01" id="p_venta" disabled>
                        <input type="hidden" name="detalles[0][articulo_id]" id="articulo_id">
                        <button type="button" class="bg-green-500 text-white p-2 rounded" onclick="addRow()">Agregar</button>
                    </div>

                    <!-- Tabla -->
                    <div class="w-full mb-4 overflow-y-auto h-[500px] border">
                        <table class="w-full" id="detalleTable">
                            <thead>
                                <tr class="bg-gray-800 text-white sticky top-0">
                                    <th class="p-2 text-center">N°</th>
                                    <th class="p-2 text-center">Código</th>
                                    <th class="p-2 text-center">Artículo</th>
                                    <th class="p-2 text-center">Cantidad</th>
                                    <th class="p-2 text-center">Precio Unitario</th>
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

                <!-- Card Derecha: Tipo de Pago -->
                <div class="w-1/3 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Datos</h3>
                    <div class="mb-4">
                        <label for="tipo_pago" class="block text-sm font-medium text-gray-700">Tipo de Pago</label>
                        <select name="tipo_pago" id="tipo_pago" class="border rounded p-2 w-full" required>
                            <option value="efectivo">Efectivo</option>
                            <option value="yape">Yape</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="total_card" class="block text-sm font-medium text-gray-700">Codigo</label>
                        <input name="codigo" type="text" id="codigo" class="border rounded p-2 w-full bg-gray-300" value="{{ $codigo }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label for="total_card" class="block text-sm font-medium text-gray-700">Total S/</label>
                        <input type="number" id="total_card" class="border rounded p-2 w-full " value="0.00" readonly>
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
            const switchElement = this.nextElementSibling;
            const knobElement = document.getElementById('scannerKnob');
            if (scannerMode) {
                switchElement.classList.remove('bg-gray-300');
                switchElement.classList.add('bg-green-500');
                knobElement.classList.remove('translate-x-1');
                knobElement.classList.add('translate-x-7');
                document.getElementById('searchArticulo').focus();
            } else {
                switchElement.classList.remove('bg-green-500');
                switchElement.classList.add('bg-gray-300');
                knobElement.classList.remove('translate-x-7');
                knobElement.classList.add('translate-x-1');
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
                        addRow(); // Agregar directamente a la tabla
                    } else {
                      
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
            let cantidad = parseInt(document.getElementById('cantidad').value) || 1;
            const precioUnitario = parseFloat(document.getElementById('p_venta').value) || 0.00;

            if (!articuloId || cantidad <= 0 || precioUnitario <= 0) {
                alert('Por favor, seleccione un artículo válido.');
                return;
            }

            // Buscar si el artículo ya existe en la tabla
            let row = document.querySelector(`#detalleBody tr[data-articulo-id="${articuloId}"]`);
            if (row) {
                let currentCantidad = parseInt(row.cells[3].textContent);
                cantidad += currentCantidad;
                let newSubtotal = (cantidad * precioUnitario).toFixed(2);
                row.cells[3].textContent = cantidad;
                row.cells[5].textContent = newSubtotal;
            } else {
                rowIndex++;
                const subtotal = (cantidad * precioUnitario).toFixed(2);
                const newRow = `
                    <tr class="border-t" data-articulo-id="${articuloId}">
                        <td class="p-2 text-center">${rowIndex}</td>
                        <td class="p-2 text-center">${articuloNombre.split(' ')[0] || ''}</td> <!-- Código aproximado -->
                        <td class="p-2 text-center">${articuloNombre}</td>
                        <td class="p-2 text-center">${cantidad}</td>
                        <td class="p-2 text-center">${precioUnitario.toFixed(2)}</td>
                        <td class="p-2 text-center">${subtotal}</td>
                        <td class="p-2 text-center"><button class="text-red-500" onclick="removeRow(this)">✕</button></td>
                    </tr>`;
                document.getElementById('detalleBody').insertAdjacentHTML('beforeend', newRow);
            }

            updateTotal();

            // Limpiar campos
            if (!scannerMode) {
                document.getElementById('articulo_id').value = '';
                document.getElementById('searchArticulo').value = '';
            }
            document.getElementById('cantidad').value = 1;
             document.getElementById('p_venta').value = '0.00';
        }

        function removeRow(button) {
            button.closest('tr').remove();
            updateTotal();
            rowIndex = document.querySelectorAll('#detalleBody tr').length;
            document.querySelectorAll('#detalleBody tr').forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#detalleBody tr').forEach(row => {
                const subtotal = parseFloat(row.cells[5].textContent) || 0;
                total += subtotal;
            });
            document.getElementById('total').textContent = total.toFixed(2);
            document.getElementById('total_card').value = total.toFixed(2);
        }

        // Enviar datos de la tabla al formulario antes de submit
        document.getElementById('ventaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const detalles = [];
            document.querySelectorAll('#detalleBody tr').forEach(row => {
                const articuloId = row.getAttribute('data-articulo-id');
                const cantidad = parseInt(row.cells[3].textContent);
                const precioUnitario = parseFloat(row.cells[4].textContent);
                const subtotal = parseFloat(row.cells[5].textContent);

                detalles.push({
                    articulo_id: articuloId,
                    cantidad: cantidad,
                    precio_unitario: precioUnitario,
                    subtotal: subtotal
                });

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = `detalles[${detalles.length - 1}][articulo_id]`;
                inputId.value = articuloId;
                this.appendChild(inputId);

                const inputCantidad = document.createElement('input');
                inputCantidad.type = 'hidden';
                inputCantidad.name = `detalles[${detalles.length - 1}][cantidad]`;
                inputCantidad.value = cantidad;
                this.appendChild(inputCantidad);

                const inputPrecio = document.createElement('input');
                inputPrecio.type = 'hidden';
                inputPrecio.name = `detalles[${detalles.length - 1}][p_venta]`;
                inputPrecio.value = precioUnitario;
                this.appendChild(inputPrecio);

                const inputSubtotal = document.createElement('input');
                inputSubtotal.type = 'hidden';
                inputSubtotal.name = `detalles[${detalles.length - 1}][subtotal]`;
                inputSubtotal.value = subtotal;
                this.appendChild(inputSubtotal);
            });

            if (detalles.length === 0) {
                alert('Debe agregar al menos un artículo.');
                return;
            }

            this.submit();
        });
    </script>
</x-app-layout>