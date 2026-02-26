<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Ventas</h2>

        <!-- Filtros -->
        <div class="mb-4 flex space-x-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" id="start_date" name="start_date" class="border rounded p-2" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" id="end_date" name="end_date" class="border rounded p-2" value="{{ request('end_date', now()->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700">Código de Venta</label>
                <input type="text" id="codigo" name="codigo" class="border rounded p-2" value="{{ request('codigo') }}" placeholder="Código de venta">
            </div>
            <button id="filterButton" class="bg-blue-500 text-white p-2 rounded mt-6">Filtrar</button>
        </div>

        <!-- Tabla de Ventas -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-2 text-center border border-gray-300">N°</th>
                        <th class="p-2 text-center border border-gray-300">Código Venta</th>
                        <th class="p-2 text-center border border-gray-300">Trabajador</th>
                        <th class="p-2 text-center border border-gray-300">Total</th>
                        <th class="p-2 text-center border border-gray-300">Tipo Pago</th>
                        <th class="p-2 text-center border border-gray-300">Fecha</th>
                        <th class="p-2 text-center border border-gray-300">Acciones</th>
                    </tr>
                </thead>
                <tbody id="ventasTableBody">
                    @forelse ($ventas as $index => $venta)
                    <tr class="hover:bg-gray-100">
                        <td class="p-2 text-center border border-gray-300">{{ $ventas->firstItem() + $index }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ $venta->codigo }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ $venta->user->name ?? 'N/A' }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ number_format($venta->total, 2) }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ $venta->tipo_pago }}</td>
                        <td class="p-2 text-center border border-gray-300">{{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }}</td>
                        <td class="p-2 text-center border border-gray-300">
                            <button class="bg-gray-500 text-white p-1 rounded view-detail" data-id="{{ $venta->detalleVentas->first()->id }}">
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
                {{ $ventas->withQueryString()->links() }}
            </div>
        </div>

        <!-- Modal para Detalle de Venta -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-3xl max-h-[80vh] overflow-y-auto relative shadow-lg">
                <button id="closeModal" class="absolute top-2 right-2 text-gray-500 p-2 rounded w-15 h-auto"><i class="fa-solid fa-xmark"></i></button>
                <h3 class="text-xl font-bold mb-4">Detalles de Venta</h3>
                <hr class="mb-5">
                <div id="modalContent" class="space-y-4">
                    <!-- Contenido del modal se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadModalDetail(detalleId) {
            fetch(`/ventas/detalles/${detalleId}`)
                .then(response => response.json())
                .then(data => {
                    const modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `
                        <div class="flex items-center justify-between">
                            <p><strong>Usuario:</strong> ${data.venta.user_nombre}</p>
                            <p><strong>Fecha:</strong> ${new Date(data.venta.created_at).toLocaleDateString()}</p>
                            <p><strong>Código:</strong> ${data.venta.codigo}</p>
                        </div>
                        <h4 class="text-lg font-semibold mt-4">Detalles de la Venta</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full bg-white border-collapse border border-gray-300 mt-2">
                                <thead>
                                    <tr class="bg-gray-800 text-white">
                                        <th class="p-2 text-center border border-gray-300">Artículo</th>
                                        <th class="p-2 text-center border border-gray-300">Cantidad</th>
                                        <th class="p-2 text-center border border-gray-300">Precio Unitario</th>
                                        <th class="p-2 text-center border border-gray-300">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.detalles.map(detalle => `
                                        <tr class="hover:bg-gray-100">
                                            <td class="p-2 text-center border border-gray-300">${detalle.articulo_nombre}</td>
                                            <td class="p-2 text-center border border-gray-300">${detalle.cantidad}</td>
                                            <td class="p-2 text-center border border-gray-300">S/ ${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                                            <td class="p-2 text-center border border-gray-300">S/ ${parseFloat(detalle.subtotal).toFixed(2)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <p><strong>Total:</strong> S/ ${parseFloat(data.venta.total).toFixed(2)}</p>
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
            const codigo = document.getElementById('codigo').value;
            window.location.href = `/ventas/detalles?start_date=${startDate}&end_date=${endDate}&codigo=${codigo}`;
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('ventasTableBody').addEventListener('click', function(e) {
                const button = e.target.closest('.view-detail');
                if (button) {
                    const detalleId = button.getAttribute('data-id');
                    loadModalDetail(detalleId);
                }
            });
        });
    </script>
</x-app-layout>