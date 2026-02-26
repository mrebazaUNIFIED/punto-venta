<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class DetalleCompraController extends Controller
{
    /**
     * Mostrar listado de compras filtradas por fecha.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $compras = Compra::with('proveedor')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59'))
            ->orderByDesc('created_at')
            ->paginate(10);

        $data = $compras->through(function ($compra) {
            return [
                'id' => $compra->id,
                'folio' => $compra->folio,
                'proveedor_nombre' => $compra->proveedor->nombre,
                'total' => (float) $compra->total,
                'estado' => $compra->estado,
                'created_at' => $compra->created_at,
            ];
        });
        return view('pages.compras.detalle', compact('data'));
    }

    /**
     * Mostrar detalle de una compra específica.
     */
    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'detalle.articulo'])->findOrFail($id);

        $data = [
            'id' => $compra->id,
            'folio' => $compra->folio,
            'proveedor_nombre' => $compra->proveedor->nombre,
            'total' => (float) $compra->total,
            'estado' => $compra->estado,
            'created_at' => $compra->created_at,
            'detalles' => $compra->detalle->map(function ($detalle) {
                return [
                    'articulo_nombre' => $detalle->articulo->nombre,
                    'cantidad' => (int) $detalle->cantidad,
                    'precio' => (float) $detalle->precio,
                    'subtotal' => (float) $detalle->subtotal,
                ];
            }),
        ];

        return response()->json($data);
    }
}
