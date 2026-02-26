<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Articulo;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Caja;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class EmpresaController extends Controller
{
    public function index()
    {
        $empresa = Empresa::first(); // Suponemos que solo hay una empresa
        return view('pages.configuracion.datos', compact('empresa'));
    }

    public function reportes(Request $request)
    {
        $tipo = $request->input('tipo');
        $data = [];
        $labels = [];
        $topProductos = collect();

        // Filtro por mes
        if ($tipo === 'mes' && $request->has('mes')) {
            $mes = $request->mes;
            $ventas = Venta::selectRaw('DAY(created_at) as dia, SUM(total) as total')
                ->whereMonth('created_at', $mes)
                ->whereYear('created_at', now()->year)
                ->groupBy('dia')
                ->orderBy('dia')
                ->get();

            $labels = $ventas->pluck('dia');
            $data = $ventas->pluck('total');

            $topProductos = DetalleVenta::select('articulo_id', DB::raw('SUM(cantidad) as total'))
                ->whereHas('venta', function ($query) use ($mes) {
                    $query->whereMonth('created_at', $mes)
                        ->whereYear('created_at', now()->year);
                })
                ->groupBy('articulo_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'nombre' => $item->articulo->nombre ?? 'Desconocido',
                        'total' => $item->total
                    ];
                });
        }

        // Filtro por rango de fechas
        if ($tipo === 'rango' && $request->has(['desde', 'hasta'])) {
            $desde = $request->desde;
            $hasta = $request->hasta;

            $ventas = Venta::selectRaw('DATE(created_at) as fecha, SUM(total) as total')
                ->whereBetween('created_at', [$desde, $hasta])
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get();

            $labels = $ventas->pluck('fecha');
            $data = $ventas->pluck('total');

            $topProductos = DetalleVenta::select('articulo_id', DB::raw('SUM(cantidad) as total'))
                ->whereHas('venta', function ($query) use ($desde, $hasta) {
                    $query->whereBetween('created_at', [$desde, $hasta]);
                })
                ->groupBy('articulo_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'nombre' => $item->articulo->nombre ?? 'Desconocido',
                        'total' => $item->total
                    ];
                });
        }

        return view('pages.reportes.reportes', compact('labels', 'data', 'tipo', 'topProductos'));
    }

    public function dashboard()
    {
        // Totales rápidos
        $ventasDia = Venta::whereDate('created_at', today())->sum('total');
        $totalArticulos = Articulo::count();
        $comprasDia = Compra::whereDate('created_at', today())->count();
        $cajasAbiertas = Caja::where('estado', 'abierta')->count();

        // Top 10 productos más vendidos
        $topProductos = DetalleVenta::select('articulo_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('articulo_id')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $articulo = Articulo::find($item->articulo_id);
                return [
                    'nombre' => $articulo ? $articulo->nombre : 'Desconocido',
                    'total_vendido' => $item->total_vendido
                ];
            });

        // Ventas últimos 7 días (incluyendo días sin ventas con 0)
        $fechas = collect(range(0, 6))->map(function ($i) {
            return now()->subDays(6 - $i)->toDateString();
        });

        $ventasRaw = Venta::selectRaw('DATE(created_at) as fecha, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('fecha')
            ->pluck('total', 'fecha');

        $ventasUltimos7Dias = $fechas->mapWithKeys(function ($fecha) use ($ventasRaw) {
            return [$fecha => $ventasRaw->get($fecha, 0)];
        });

        // Ventas de hoy y ayer para comparación
        $ventasHoy = Venta::whereDate('created_at', today())->sum('total');
        $ventasAyer = Venta::whereDate('created_at', now()->subDay())->sum('total');

        return view('pages.dashboard', compact(
            'ventasDia',
            'totalArticulos',
            'comprasDia',
            'cajasAbiertas',
            'topProductos',
            'ventasUltimos7Dias',
            'ventasHoy',
            'ventasAyer'
        ));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Empresa $empresa)
    {
        //
    }

    public function edit(Empresa $empresa)
    {
        //
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'ruc' => 'required|string|max:11',
            'direccion_fiscal' => 'required|string|max:255',
            'ubigeo' => 'nullable|string|max:6',
            'departamento' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'distrito' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($empresa->logo) {
                Storage::disk('public')->delete('logos/' . $empresa->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = basename($path);
        }

        $empresa->update($data);

        return redirect()->route('configuracion.empresa.index')
            ->with('success', 'Datos de la empresa actualizados correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        //
    }
}
