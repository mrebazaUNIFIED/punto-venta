<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Articulo;
use Illuminate\Http\Request;
use App\Exports\InventarioExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventario::with('articulo');

        if ($request->filled('buscar')) {
            $query->whereHas('articulo', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                    ->orWhere('codigo', 'like', '%' . $request->buscar . '%');
            });
        }

        $inventario = $query->paginate(8)->withQueryString();

        return view('pages.almacen.inventario', compact('inventario'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $inventario->stock = $request->stock;
        $inventario->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Se ha actualizado el stock correctamente']);
        }

        return redirect()->route('almacen.inventario.index')->with('success', 'Stock actualizado correctamente');
    }

    public function exportExcel()
    {
        return Excel::download(new InventarioExport, 'inventario.xlsx');
    }

    public function exportPdf()
    {
        $inventario = Inventario::with('articulo')->get();
        $pdf = Pdf::loadView('pdf.inventario', compact('inventario'));
        return $pdf->download('inventario.pdf');
    }
}
