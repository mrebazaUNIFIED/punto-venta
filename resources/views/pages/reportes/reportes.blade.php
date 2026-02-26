<x-app-layout>
    <div class="p-6 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Reportes de Ventas</h2>

        <!-- Filtros -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Filtro por mes -->
            <form method="GET" action="{{ route('empresa.reportes') }}" class="bg-white shadow p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Filtrar por Mes</h3>
                <input type="hidden" name="tipo" value="mes">
                <select name="mes" class="w-full p-2 border rounded">
                    <option value="">Seleccione un mes</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Buscar</button>
            </form>

            <!-- Filtro por rango de fechas -->
            <form method="GET" action="{{ route('empresa.reportes') }}" class="bg-white shadow p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Filtrar por Rango de Fechas</h3>
                <input type="hidden" name="tipo" value="rango">
                <label class="block mb-1">Desde:</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="w-full p-2 border rounded mb-2">
                <label class="block mb-1">Hasta:</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="w-full p-2 border rounded mb-2">
                <button type="submit"
                    class="mt-2 w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">Buscar</button>
            </form>
        </div>

        <!-- Gráfico de ventas -->
        @if(!empty($data) && count($data))
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Ventas Totales</h3>
                <canvas id="ventasChart" height="100"></canvas>
            </div>
        @elseif(request()->has('tipo'))
            <div class="bg-yellow-100 text-yellow-700 p-4 rounded">
                No hay datos disponibles para los filtros seleccionados.
            </div>
        @endif

        <!-- Gráfico de top productos -->
        @if($topProductos->count())
            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Top 5 Productos Más Vendidos</h3>
                <canvas id="topProductosChart" height="100"></canvas>
            </div>
        @endif
    </div>

    <!-- Scripts de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(!empty($data) && count($data))
    <script>
        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total de Ventas',
                    data: {!! json_encode($data) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
    @endif

    @if($topProductos->count())
    <script>
        const ctx2 = document.getElementById('topProductosChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topProductos->pluck('nombre')) !!},
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: {!! json_encode($topProductos->pluck('total')) !!},
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
    @endif
</x-app-layout>
