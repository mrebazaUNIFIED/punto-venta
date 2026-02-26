<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Cuadros de resumen --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                @php
                    $cards = [
                        ['label' => 'Ventas del día', 'value' => number_format($ventasDia, 2), 'icon' => '📈', 'color' => 'green'],
                        ['label' => 'Total de productos', 'value' => $totalArticulos, 'icon' => '📦', 'color' => 'blue'],
                        ['label' => 'Compras del día', 'value' => $comprasDia, 'icon' => '💰', 'color' => 'yellow'],
                   
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-center">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $card['label'] }}</h3>
                            <span class="text-{{ $card['color'] }}-500 text-xl">{{ $card['icon'] }}</span>
                        </div>
                        
                        <p class="inline-block bg-gray-600 text-white font-bold text-xs px-2 py-1 rounded-md">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Gráficos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Total de Ventas en los Últimos 7 Días</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="ventasUltimos7DiasChart" class="w-full"></canvas>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ventas entre ayer y hoy</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="ventasComparativoChart" class="w-full"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top 10 productos --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Productos Más Vendidos</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidades Vendidas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($topProductos as $producto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $producto['nombre'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $producto['total_vendido'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventasUltimos7DiasLabels = {!! json_encode(array_keys($ventasUltimos7Dias->toArray())) !!};
        const ventasUltimos7DiasData = {!! json_encode(array_values($ventasUltimos7Dias->toArray())) !!};

        // Gráfico de ventas últimos 7 días
        const ctx1 = document.getElementById('ventasUltimos7DiasChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ventasUltimos7DiasLabels,
                datasets: [{
                    label: 'Ventas diarias (S/)',
                    data: ventasUltimos7DiasData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '$' + value
                        }
                    }
                }
            }
        });

        // Gráfico comparativo ayer vs hoy
        const ctx2 = document.getElementById('ventasComparativoChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Ayer', 'Hoy'],
                datasets: [{
                    label: 'Ventas (S/)',
                    data: [{{ $ventasAyer ?: 0 }}, {{ $ventasHoy ?: 0 }}],
                    backgroundColor: ['#9c27b0', '#4caf50']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '$' + value
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
