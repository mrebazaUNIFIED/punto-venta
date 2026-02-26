<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Detalles de Compras</h2>

        <!-- Filtros por fechas -->
        <div class="mb-4 flex space-x-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" id="start_date" class="border rounded p-2" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" id="end_date" class="border rounded p-2" value="{{ request('end_date', now()->format('Y-m-d')) }}">
            </div>
            <button id="filterButton" class="bg-blue-500 text-white p-2 rounded mt-6">Filtrar</button>
        </div>

        <!-- Tabla de Detalles de Compras -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-2 text-center border border-gray-300">N°</th>
                        <th class="p-2 text-center border border-gray-300">Folio</th>
                        <th class="p-2 text-center border border-gray-300">Proveedor</th>
                        <th class="p-2 text-center border border-gray-300">Total</th>
                        <th class="p-2 text-center border border-gray-300">Estado</th>
                        <th class="p-2 text-center border border-gray-300">Fecha</th>
                        <th class="p-2 text-center border border-gray-300">Acciones</th>
                    </tr>
                </thead>
                <tbody id="detalleTableBody">
                    @forelse ($data as $index => $compra)
                    <tr class="hover:bg-gray-100">
                        <td class="p-2 text-center border border-gray-300">{{ $data->firstItem() + $index }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ $compra['folio'] }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ $compra['proveedor_nombre'] }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ number_format($compra['total'], 2) }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ $compra['estado'] ? 'Activo' : 'Inactivo' }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ \Carbon\Carbon::parse($compra['created_at'])->format('d/m/Y') }}</td>
                        <td class="p-2 text-center border border-gray-300">
                            <button class="bg-gray-500 text-white p-1 rounded view-detail" data-id="{{ $compra['id'] }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-2 text-center border border-gray-300">No hay datos disponibles.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $data->withQueryString()->links() }}
            </div>
        </div>

        <!-- Modal para Detalle de Compra -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden  items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-3xl max-h-[80vh] overflow-y-auto relative shadow-lg">
                <button id="closeModal" class="absolute top-2 right-2  text-gray-500 p-2 rounded w-15 h-auto"><i class="fa-solid fa-xmark"></i></button>
                <h3 class="text-xl font-bold mb-4">Entrada de productos</h3>
                <hr class="mb-5">
                <div id="modalContent" class="space-y-4">
                    <!-- Contenido del modal se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadModalDetail(compraId) {
            fetch(`/compras/detalle-productos/${compraId}`)
                .then(response => response.json())
                .then(data => {
                    const modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `
                       <div class="flex items-center justify-between">
                        <p><strong>Proveedor:</strong> ${data.proveedor_nombre}</p>
                        <p><strong>Fecha:</strong> ${new Date(data.created_at).toLocaleDateString()}</p>
                         <p><strong>Folio:</strong> ${data.folio}</p>
                       </div>
                       
                        <h4 class="text-lg font-semibold mt-4">Ingresos</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border-collapse border border-gray-300 mt-2">
                                <thead>
                                    <tr class="bg-gray-800 text-white">
                                        <th class="p-2 text-center border border-gray-300">Artículo</th>
                                        <th class="p-2 text-center border border-gray-300">Cantidad</th>
                                        <th class="p-2 text-center border border-gray-300">Precio Compra</th>
                                        <th class="p-2 text-center border border-gray-300">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.detalles.map(detalle => `
                                        <tr class="hover:bg-gray-100">
                                            <td class="p-2 text-center border border-gray-300">${detalle.articulo_nombre}</td>
                                            <td class="p-2 text-center border border-gray-300">${detalle.cantidad}</td>
                                            <td class="p-2 text-center border border-gray-300">S/ ${parseFloat(detalle.precio).toFixed(2)}</td>
                                            <td class="p-2 text-center border border-gray-300">S/ ${parseFloat(detalle.subtotal).toFixed(2)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>

                         <div class="text-end">
                          <p><strong>Total:</strong> S/ ${parseFloat(data.total).toFixed(2)}</p>
                         </div>
                    `;
                    document.getElementById('detailModal').classList.remove('hidden');
                    document.getElementById('detailModal').classList.add('flex');
                })
                .catch(error => console.error('Error al cargar el detalle:', error));
        }

        document.getElementById('filterButton').addEventListener('click', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            window.location.href = `/compras/detalle-productos?start_date=${startDate}&end_date=${endDate}`;
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('detalleTableBody').addEventListener('click', function(e) {
                const button = e.target.closest('.view-detail');
                if (button) {
                    const compraId = button.getAttribute('data-id');
                    loadModalDetail(compraId);
                }
            });
        });
    </script>
</x-app-layout>