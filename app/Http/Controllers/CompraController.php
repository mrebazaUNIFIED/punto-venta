<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Articulo;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        $articulos = Articulo::with('inventario')->get(); 
        $fechaHoy = date('d-m-Y');
        $conteoHoy = Compra::whereDate('created_at', today())->count();
        $siguienteFolio = $fechaHoy . '-' . ($conteoHoy + 1);
        return view('pages.compras.entrada', compact('proveedores', 'siguienteFolio', 'articulos'));
    }

    public function searchArticulos(Request $request)
    {
        $query = $request->input('query');
        $articulos = Articulo::with('inventario')
            ->where('nombre', 'like', "%{$query}%")
            ->orWhere('codigo', 'like', "%{$query}%")
            ->get();

        return response()->json($articulos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'folio' => 'required|string',
            'proveedor_id' => 'required|exists:proveedores,id',
            'detalles' => 'required|array',
            'detalles.*.articulo_id' => 'required|exists:articulos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio' => 'required|numeric|min:0',
        ]);

        $compra = Compra::create([
            'folio' => $request->folio,
            'total' => 0,
            'estado' => 1, // Siempre asignamos 1 (true) como valor booleano
            'proveedor_id' => $request->proveedor_id,
        ]);

        $total = 0;
        foreach ($request->detalles as $detalle) {
            $articulo = Articulo::with('inventario')->find($detalle['articulo_id']);
            $nuevaCantidad = $detalle['cantidad'];
            $nuevoStock = ($articulo->inventario->stock ?? 0) + $nuevaCantidad;

            // Actualizar el inventario
            $articulo->inventario()->updateOrCreate(
                ['articulo_id' => $articulo->id],
                ['stock' => $nuevoStock]
            );

            $subtotal = $nuevaCantidad * $detalle['precio'];
            $detalleCompra = new DetalleCompra([
                'compra_id' => $compra->id,
                'articulo_id' => $detalle['articulo_id'],
                'cantidad' => $nuevaCantidad,
                'precio' => $detalle['precio'],
                'subtotal' => $subtotal,
            ]);
            $detalleCompra->save();
            $total += $subtotal;
        }

        $compra->update(['total' => $total]);

        return redirect()->back()->with('success', 'Compra registrada exitosamente');
    }
}
