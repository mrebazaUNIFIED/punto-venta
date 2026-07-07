<x-app-layout>
    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-1000">
        
        <!-- Welcome & Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-1">
                <h2 class="text-5xl font-black text-slate-950 tracking-tighter italic uppercase">Centro de Mando</h2>
                <p class="text-slate-400 font-bold text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span>
                    Sincronización en tiempo real activa. Bienvenido, {{ Auth::user()->name }}.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="px-6 py-3 bg-white/70 backdrop-blur-md border border-white rounded-[2rem] shadow-sm flex items-center gap-3">
                    <i class="fas fa-calendar-day text-brand"></i>
                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Metric Pulse Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $metrics = [
                    [
                        'label' => 'Ventas de Hoy',
                        'value' => 'S/ ' . number_format($ventasDia, 2),
                        'sub' => 'Ingreso Bruto Diario',
                        'icon' => 'fa-chart-line',
                        'color' => 'blue',
                        'trend' => '+12.5%'
                    ],
                    [
                        'label' => 'Catálogo Activo',
                        'value' => $totalArticulos . ' SKU',
                        'sub' => 'Artículos en Inventario',
                        'icon' => 'fa-box-open',
                        'color' => 'slate',
                        'trend' => 'Estable'
                    ],
                    [
                        'label' => 'Inversión Hoy',
                        'value' => 'S/ ' . number_format($comprasDia, 2),
                        'sub' => 'Compras a Proveedores',
                        'icon' => 'fa-wallet',
                        'color' => 'cyan',
                        'trend' => '-4.2%'
                    ]
                ];
            @endphp

            @foreach ($metrics as $m)
            <div class="group relative bg-white/70 backdrop-blur-xl border border-white p-10 rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden transition-all hover:scale-[1.02] hover:shadow-2xl duration-500">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-{{ $m['color'] }}-100/50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                
                <div class="relative z-10 flex flex-col h-full justify-between gap-8">
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-{{ $m['color'] }}-50 text-{{ $m['color'] }}-600 flex items-center justify-center text-2xl shadow-inner">
                            <i class="fas {{ $m['icon'] }}"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] font-mono {{ $m['trend'] === 'Estable' ? 'text-slate-400' : ($m['trend'][0] === '+' ? 'text-green-500' : 'text-red-500') }}">
                            {{ $m['trend'] }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic mb-1">{{ $m['label'] }}</h3>
                        <p class="text-4xl font-black text-slate-950 tracking-tighter italic">{{ $m['value'] }}</p>
                        <p class="text-[10px] font-bold text-slate-300 mt-2 italic">{{ $m['sub'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Analytical Intelligence Section -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            <!-- Performance Graph -->
            <div class="xl:col-span-8 bg-slate-950 p-12 rounded-[4rem] shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-br from-blue-600/10 to-transparent pointer-events-none"></div>
                
                <div class="relative z-10 space-y-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-white text-xl font-black italic tracking-tight">Rendimiento Lineal</h3>
                            <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mt-1 italic">Últimos 7 días de operación</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 px-6 py-2 rounded-full text-white text-[9px] font-black uppercase tracking-widest">
                            High Fidelity Data
                        </div>
                    </div>

                    <div class="h-[350px]">
                        <canvas id="ventasUltimos7DiasChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Comparison Widget -->
            <div class="xl:col-span-4 bg-white/70 backdrop-blur-xl border border-white p-12 rounded-[4rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] flex flex-col justify-between">
                <div>
                    <h3 class="text-slate-950 text-xl font-black italic tracking-tight">Cruce Operativo</h3>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1 italic">Ventas comparativas Ayer vs Hoy</p>
                </div>

                <div class="h-[250px] my-4">
                    <canvas id="ventasComparativoChart"></canvas>
                </div>

                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 italic">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Proyección Sugerida</p>
                    @php 
                        $diff = $ventasHoy - $ventasAyer;
                        $percent = $ventasAyer > 0 ? ($diff / $ventasAyer) * 100 : 0;
                    @endphp
                    <p class="text-sm font-black text-slate-900 leading-tight">
                        @if($diff > 0)
                            Tendencia positiva del <span class="text-green-600">{{ number_format($percent, 1) }}%</span> respecto al cierre anterior.
                        @else
                            Ajuste de mercado del <span class="text-red-500">{{ number_format(abs($percent), 1) }}%</span> detectado.
                        @endif
                    </p>
                </div>
            </div>

        </div>

        <!-- Leaderboard: Top Products -->
        <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[4rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="p-12 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-slate-950 tracking-tighter italic uppercase">Top de Rotación</h3>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] mt-1 italic">Líderes de desplazamiento en salón</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-brand flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-crown"></i>
                </div>
            </div>

            <div class="overflow-x-auto px-12 pb-12">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th class="py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic">Rango</th>
                            <th class="py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic">Articulo de Venta</th>
                            <th class="py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic">Ud. Desplazadas</th>
                            <th class="py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] italic">Performance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($topProductos as $index => $producto)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl font-black text-xs {{ $index < 3 ? 'bg-brand text-white shadow-lg shadow-blue-100' : 'bg-slate-100 text-slate-400' }}">
                                    0{{ $index + 1 }}
                                </span>
                            </td>
                            <td class="py-6">
                                <p class="text-sm font-black text-slate-900 italic tracking-tight uppercase">{{ $producto['nombre'] }}</p>
                                <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-0.5">Producto Estrella</p>
                            </td>
                            <td class="py-6 text-center">
                                <span class="px-5 py-2 bg-slate-900 text-white rounded-full font-black text-xs italic">
                                    {{ $producto['total_vendido'] }}
                                </span>
                            </td>
                            <td class="py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        @php $max = $topProductos->first()['total_vendido'] ?: 1; @endphp
                                        <div class="h-full bg-brand rounded-full" style="width: {{ ($producto['total_vendido'] / $max) * 100 }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 italic">{{ number_format(($producto['total_vendido'] / $max) * 100, 0) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center opacity-30 italic">
                                <i class="fas fa-layer-group text-5xl mb-4"></i>
                                <p class="text-xs font-black uppercase tracking-[0.4em]">Sin data proyectada</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart Engine -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventas7Labels = {!! json_encode(array_keys($ventasUltimos7Dias->toArray())) !!};
        const ventas7Data = {!! json_encode(array_values($ventasUltimos7Dias->toArray())) !!};

        // Linear Performance Chart
        const ctx1 = document.getElementById('ventasUltimos7DiasChart').getContext('2d');
        const gradient1 = ctx1.createLinearGradient(0, 0, 0, 400);
        gradient1.addColorStop(0, 'rgba(37, 99, 235, 0.4)');
        gradient1.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ventas7Labels,
                datasets: [{
                    label: 'Ventas (S/)',
                    data: ventas7Data,
                    borderColor: '#2563eb',
                    borderWidth: 4,
                    backgroundColor: gradient1,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#64748b', font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { weight: 'bold' } }
                    }
                }
            }
        });

        // Comparative Chart
        const ctx2 = document.getElementById('ventasComparativoChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Ayer', 'Hoy'],
                datasets: [{
                    data: [{{ $ventasAyer ?: 0 }}, {{ $ventasHoy ?: 0 }}],
                    backgroundColor: ['#f1f5f9', '#2563eb'],
                    borderRadius: 20,
                    borderSkipped: false,
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { display: false },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { weight: 'black', size: 10 } }
                    }
                }
            }
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</x-app-layout>
