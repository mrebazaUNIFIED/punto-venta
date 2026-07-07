<x-app-layout>
    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <h2 class="text-5xl font-black text-slate-950 tracking-tighter italic uppercase">Inteligencia de Negocio
                </h2>
                <p class="text-slate-400 font-bold text-sm">Visualiza el rendimiento comercial y las tendencias de
                    consumo global.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                    <i class="fas fa-brain text-brand"></i>
                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Análisis Pro
                        Certificado</span>
                </div>
            </div>
        </div>

        <!-- Advanced Analytics Filters -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            <!-- Filter: Monthly Slice -->
            <div
                class="bg-white/70 backdrop-blur-xl border border-white p-10 rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] relative overflow-hidden group">
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50/50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000">
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between gap-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-950 italic tracking-tight">Corte Mensual</h3>
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-50 text-brand flex items-center justify-center text-xl">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('empresa.reportes') }}" class="space-y-6">
                        <input type="hidden" name="tipo" value="mes">
                        <div class="space-y-2">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 italic block text-left">Seleccionar
                                Periodo:</label>
                            <select name="mes"
                                class="w-full px-8 py-5 bg-slate-50 border-none rounded-[2rem] focus:ring-[10px] focus:ring-blue-500/5 text-slate-900 font-bold appearance-none outline-none cursor-pointer transition-all">
                                <option value="" class="font-bold">Elegir un mes...</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }} class="font-bold">
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full py-5 bg-slate-900 hover:bg-brand text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] shadow-xl transition-all active:scale-95">
                            Sincronizar Periodo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filter: Strategic Range -->
            <div class="bg-brand p-10 rounded-[3.5rem] shadow-2xl shadow-blue-100 relative overflow-hidden group">
                <div
                    class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000">
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between gap-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-white italic tracking-tight">Rango Estratégico</h3>
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center text-xl">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('empresa.reportes') }}" class="grid grid-cols-2 gap-4">
                        <input type="hidden" name="tipo" value="rango">
                        <div class="col-span-1 space-y-2">
                            <label
                                class="text-[9px] font-black text-blue-200 uppercase tracking-widest ml-1 italic block text-left">Desde:</label>
                            <input type="date" name="desde" value="{{ request('desde') }}"
                                class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-[1.5rem] text-white font-black outline-none focus:bg-white/20 transition-all">
                        </div>
                        <div class="col-span-1 space-y-2 text-left">
                            <label
                                class="text-[9px] font-black text-blue-200 uppercase tracking-widest ml-1 italic block text-left">Hasta:</label>
                            <input type="date" name="hasta" value="{{ request('hasta') }}"
                                class="w-full px-6 py-4 bg-white/10 border border-white/20 rounded-[1.5rem] text-white font-black outline-none focus:bg-white/20 transition-all">
                        </div>
                        <div class="col-span-2 pt-2">
                            <button type="submit"
                                class="w-full py-5 bg-white text-brand hover:bg-slate-950 hover:text-white rounded-[2rem] font-black uppercase tracking-[0.2em] text-[10px] shadow-2xl transition-all active:scale-95">
                                Analizar Segmento
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- High-Fidelity Charting Engine -->
        @if(!empty($data) && count($data))
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

                <!-- Main Sales Velocity Map -->
                <div class="xl:col-span-8 bg-slate-950 p-12 rounded-[4rem] shadow-2xl relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-full h-full bg-gradient-to-br from-blue-600/10 to-transparent pointer-events-none">
                    </div>
                    <div class="relative z-10 space-y-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-white text-2xl font-black italic tracking-tight uppercase">Velocidad de
                                    Venta</h3>
                                <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mt-1 italic">
                                    Proyección consolidada de ingresos brutos</p>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-4 h-4 rounded-full bg-brand animate-pulse"></div>
                                <div class="w-4 h-4 rounded-full bg-slate-800"></div>
                            </div>
                        </div>
                        <div class="h-[400px]">
                            <canvas id="ventasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Product Mix Distribution -->
                <div
                    class="xl:col-span-4 bg-white/70 backdrop-blur-xl border border-white p-12 rounded-[4rem] shadow-[0_40px_80px_rgba(0,0,0,0.05)] flex flex-col justify-between">
                    <div>
                        <h3 class="text-slate-950 text-xl font-black italic tracking-tight uppercase">Mix de Consumo</h3>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1 italic">Distribución
                            de top 5 líderes</p>
                    </div>

                    <div class="h-[300px] my-8 flex items-center justify-center">
                        @if($topProductos->count())
                            <canvas id="topProductosChart"></canvas>
                        @else
                            <div class="text-center opacity-20">
                                <i class="fas fa-chart-pie text-6xl mb-4"></i>
                                <p class="text-[9px] font-black uppercase tracking-widest">Sin data de top</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @foreach($topProductos as $p)
                            <div class="flex items-center justify-between group">
                                <span
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-tighter italic group-hover:text-slate-900 transition-colors">{{ $p->nombre }}</span>
                                <span class="text-xs font-black text-brand italic">×{{ $p->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @elseif(request()->has('tipo'))
            <div class="bg-amber-50 p-20 rounded-[4rem] border border-amber-100 text-center space-y-6">
                <div
                    class="w-24 h-24 bg-amber-100 rounded-[2.5rem] flex items-center justify-center mx-auto text-amber-500 text-4xl">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-slate-900 italic tracking-tighter">Sin Información Contrastada</h4>
                    <p class="text-slate-400 font-bold text-sm mt-2">No se detectaron transacciones válidas para los
                        parámetros de búsqueda establecidos.</p>
                </div>
            </div>
        @endif

    </div>

    <!-- Analytical Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(!empty($data) && count($data))
        <script>
            const ctx = document.getElementById('ventasChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.5)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Ventas Totales (S/)',
                        data: {!! json_encode($data) !!},
                        backgroundColor: 'rgba(37, 99, 235, 0.8)',
                        borderRadius: 12,
                        borderSkipped: false,
                        hoverBackgroundColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: '"Inter", sans-serif', weight: 'black' },
                            bodyFont: { family: '"Inter", sans-serif', weight: 'bold' },
                            padding: 16,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' }, callback: v => 'S/ ' + v }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { weight: 'black', size: 10 } }
                        }
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
                        data: {!! json_encode($topProductos->pluck('total')) !!},
                        backgroundColor: [
                            '#2563eb', '#1d4ed8', '#1e40af', '#1e3a8a', '#172554'
                        ],
                        borderWidth: 0,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        </script>
    @endif
</x-app-layout>