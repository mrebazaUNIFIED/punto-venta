<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
  /**
   * Display a form to open a new cash register with a modal.
   */
  public function apertura()
  {
    $cajaAbierta = Caja::where('user_id', Auth::id())
      ->whereNull('fecha_cierre')
      ->where('estado', true)
      ->first();

    return view('pages.caja.apertura', compact('cajaAbierta'));
  }

  /**
   * Display a form to close the current cash register with a table.
   */
  public function cierre()
  {
    $cajasAbiertas = Caja::where('user_id', Auth::id())
      ->whereNull('fecha_cierre')
      ->where('estado', true)
      ->get();

    return view('pages.caja.cierre', compact('cajasAbiertas'));
  }

  /**
   * Display the history of cash registers with date filters.
   */
  public function historico(Request $request)
  {
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $historico = Caja::where('user_id', Auth::id())
      ->when($startDate, function ($query) use ($startDate) {
        return $query->where('fecha_apertura', '>=', $startDate);
      })
      ->when($endDate, function ($query) use ($endDate) {
        return $query->where('fecha_apertura', '<=', $endDate . ' 23:59:59');
      })
      ->orderBy('fecha_apertura', 'desc')
      ->get()
      ->map(function ($caja) {
        return [
          'id' => $caja->id,
          'monto_apertura' => $caja->monto_apertura,
          'monto_cierre' => $caja->monto_cierre,
          'fecha_apertura' => $caja->fecha_apertura,
          'fecha_cierre' => $caja->fecha_cierre,
          'estado' => $caja->estado ? 'Abierta' : 'Cerrada',
          'usuario_nombre' => $caja->usuario_nombre,
        ];
      });

    return view('pages.caja.historico', compact('historico', 'startDate', 'endDate'));
  }

  /**
   * Store a new cash register opening.
   */
  public function store(Request $request)
  {
    $request->validate([
      'monto_apertura' => 'required|numeric|min:0',
    ]);

    $cajaAbierta = Caja::where('user_id', Auth::id())
      ->whereNull('fecha_cierre')
      ->where('estado', true)
      ->first();

    if ($cajaAbierta) {
      return redirect()->route('caja.apertura')->with('error', 'Ya tienes una caja abierta.');
    }

    Caja::create([
      'user_id' => Auth::id(),
      'monto_apertura' => $request->monto_apertura,
      'fecha_apertura' => now(),
      'estado' => true,
    ]);

    return redirect()->route('caja.apertura')->with('success', 'Caja abierta exitosamente.');
  }

  /**
   * Update to close the cash register.
   */
  public function update(Request $request, Caja $caja)
  {
    if ($caja->user_id !== Auth::id() || !is_null($caja->fecha_cierre) || !$caja->estado) {
      return redirect()->route('caja.cierre')->with('error', 'No puedes cerrar esta caja.');
    }

    // El monto_cierre ya fue acumulado automáticamente por las ventas en efectivo
    $caja->update([
      'fecha_cierre' => now(),
      'estado' => false,
    ]);

    return redirect()->route('caja.cierre')->with('success', 'Caja cerrada exitosamente.');
  }

  public function show(Caja $caja)
  {
    if ($caja->user_id !== Auth::id()) {
      return response()->json(['error' => 'No autorizado'], 403);
    }

    $ventasEfectivo = $caja->ventas()->where('tipo_pago', 'efectivo')->sum('total');
    $ventasYape = $caja->ventas()->where('tipo_pago', 'yape')->sum('total');

    $data = [
      'id' => $caja->id,
      'monto_apertura' => (float) $caja->monto_apertura,
      'monto_cierre' => (float) $caja->monto_cierre,
      'ventas_efectivo' => (float) $ventasEfectivo,
      'ventas_yape' => (float) $ventasYape,
      'fecha_apertura' => $caja->fecha_apertura,
      'fecha_cierre' => $caja->fecha_cierre,
      'estado' => $caja->estado,
      'usuario_nombre' => $caja->usuario_nombre,
    ];

    return response()->json($data);
  }
}
