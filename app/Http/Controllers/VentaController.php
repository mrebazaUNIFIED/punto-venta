<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Articulo;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {

    $cajaAbierta = Caja::where('user_id', Auth::id())
      ->whereNull('fecha_cierre')
      ->where('estado', true)
      ->first();

    if (!$cajaAbierta) {
      return redirect()->route('caja.apertura')->with('error', 'No hay una caja abierta.');
    }


    $articulos = Articulo::with('inventario')->get();
    $ventas = Venta::with(['user', 'caja'])->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    $fechaHoy = date('d-m-Y');
    $conteoHoy = Venta::whereDate('created_at', today())->count();
    $codigo = 'V-' . $fechaHoy . '-' . ($conteoHoy + 1);
    return view('pages.ventas.posventa', compact('ventas', 'articulos', 'codigo'));
  }

  /**
   * Search articulos for autocomplete or scanner.
   */
  public function searchArticulos(Request $request)
  {
    $query = $request->input('query');
    $articulos = Articulo::with('inventario')
      ->where('nombre', 'like', "%{$query}%")
      ->orWhere('codigo', 'like', "%{$query}%")
      ->get();

    return response()->json($articulos);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $cajaAbierta = Caja::where('user_id', Auth::id())
      ->whereNull('fecha_cierre')
      ->where('estado', true)
      ->first();

    if (!$cajaAbierta) {
      return redirect()->route('caja.apertura')->with('error', 'No hay una caja abierta.');
    }

    return view('pages.ventas.posventa', compact('cajaAbierta'));
  }



  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'detalles' => 'required|array',
      'tipo_pago' => 'required|in:efectivo,yape',
    ]);

    $cajaAbierta = Caja::where('user_id', Auth::id())
      ->whereNull('fecha_cierre')
      ->where('estado', true)
      ->first();


    DB::beginTransaction();
    try {
      $venta = Venta::create([
        'user_id' => Auth::id(),
        'caja_id' => $cajaAbierta->id,
        'total' => 0,
        'tipo_pago' => $request->tipo_pago,
        'codigo' => $request->codigo,
      ]);

      $total = 0;
      foreach ($request->detalles as $detalle) {
        $articulo = Articulo::with('inventario')->find($detalle['articulo_id']);
        if (!$articulo) continue;

        $cantidad = $detalle['cantidad'];
        $precioUnitario = $articulo->p_venta;
        $subtotal = $cantidad * $precioUnitario;

        DetalleVenta::create([
          'venta_id' => $venta->id,
          'articulo_id' => $articulo->id,
          'cantidad' => $cantidad,
          'precio_unitario' => $precioUnitario,
          'subtotal' => $subtotal,
        ]);

        // Resta del stock
        $articulo->inventario->stock -= $cantidad;
        $articulo->inventario->save();

        $total += $subtotal;
      }

      $venta->total = $total;
      $venta->save();

      if ($request->tipo_pago === 'efectivo') {
        $cajaAbierta->monto_cierre = ($cajaAbierta->monto_cierre ?? $cajaAbierta->monto_apertura) + $total;
        $cajaAbierta->save();
      }

      DB::commit();
      return redirect()->route('ventas.posventa.index')->with('success', 'Venta registrada exitosamente.');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Venta $venta)
  {
    if ($venta->user_id !== Auth::id()) {
      return response()->json(['error' => 'No autorizado'], 403);
    }

    $data = [
      'id' => $venta->id,
      'user_nombre' => $venta->user->name,
      'caja_id' => $venta->caja_id,
      'total' => $venta->total,
      'tipo_pago' => $venta->tipo_pago,
      'fecha' => $venta->created_at,
      'detalles' => $venta->detalleVentas()->with('articulo')->get()->map(function ($detalle) {
        return [
          'articulo_nombre' => $detalle->articulo->nombre,
          'cantidad' => $detalle->cantidad,
          'precio_unitario' => $detalle->precio_unitario,
          'subtotal' => $detalle->subtotal,
        ];
      }),
    ];

    return response()->json($data);
  }
}
