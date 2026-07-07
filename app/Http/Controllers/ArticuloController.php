<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    public function index()
    {
        $articulos = Articulo::with('categoria', 'inventario')->paginate(8);
        $categorias = Categoria::all();
        return view('pages.almacen.artículos', compact('articulos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:articulos',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'p_compra' => 'required|numeric|min:0',
            'p_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0' // ✅ Añadir validación para el stock
        ]);

        $data = $request->all();
        $data['estado'] = 1; // Estado por defecto: Activo

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('articulos', 'public');
            $data['imagen'] = basename($path);
        }

        // Crear artículo
        $articulo = Articulo::create($data);

        // Crear inventario asociado
        Inventario::create([
            'articulo_id' => $articulo->id,
            'stock' => $request->stock
        ]);

        return redirect()->route('almacen.articulo.index')
            ->with('success', 'Artículo creado exitosamente');
    }

    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:articulos,codigo,' . $articulo->id,
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'p_compra' => 'required|numeric|min:0',
            'p_venta' => 'required|numeric|min:0',
        ]);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            if ($articulo->imagen) {
                Storage::disk('public')->delete('articulos/' . $articulo->imagen);
            }
            $path = $request->file('imagen')->store('articulos', 'public');
            $data['imagen'] = basename($path);
        }

        $articulo->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Articulo $articulo)
    {
        if ($articulo->imagen) {
            Storage::disk('public')->delete('articulos/' . $articulo->imagen);
        }

        $articulo->delete();

        return response()->json(['success' => true]);
    }
}
