<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Venta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['user', 'detalleVentas']);

        // Apply date filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Apply sale code filter
        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }

        $ventas = $query->orderByDesc('created_at')->paginate(10);
        return view("pages.ventas.detalles", compact('ventas'));
    }

    public function show(DetalleVenta $detalleVenta)
    {
        $venta = $detalleVenta->venta->load(['user', 'detalleVentas.articulo']);
        return response()->json([
            'venta' => [
                'id' => $venta->id,
                'codigo' => $venta->codigo,
                'total' => $venta->total,
                'created_at' => $venta->created_at,
                'tipo_pago' => $venta->tipo_pago,
                'user_nombre' => $venta->user->name ?? 'N/A',
            ],
            'detalles' => $venta->detalleVentas->map(function ($detalle) {
                return [
                    'articulo_nombre' => $detalle->articulo->nombre ?? 'N/A',
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'subtotal' => $detalle->subtotal,
                ];
            })->toArray(),
        ]);
    }

    // Other methods remain unchanged
}