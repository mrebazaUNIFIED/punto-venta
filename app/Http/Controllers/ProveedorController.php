<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $proveedores = Proveedor::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('telefono', 'like', "%{$search}%");
        })->orderBy('id', 'desc')->paginate(5)->appends(['search' => $search]);

        return view('pages.compras.proveedores', compact('proveedores'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        Proveedor::create($request->only('nombre', 'telefono'));

        return redirect()->route('compras.proveedor.index')->with('success', 'Proveedor registrado correctamente.');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $proveedor->update($request->only('nombre', 'telefono'));

        return redirect()->route('compras.proveedor.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('compras.proveedor.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
