<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::paginate(5);
        return view('pages.almacen.categorías', compact('categorias'))
            ->with('categoriasData', $categorias->items()); // Pasar datos para la vista inicial
    }

    // Añade un método para devolver JSON
    public function getCategorias()
    {
        $categorias = Categoria::paginate(5);
        return response()->json(['categorias' => $categorias->items()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        Categoria::create($validatedData);

        return redirect()->route('almacen.categoria.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $categoria->update($validatedData);

        // Devolver respuesta JSON consistente
        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente.',
            'categoria' => $categoria
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        // Devolver respuesta JSON consistente
        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente.',
            'deleted_id' => $categoria->id
        ]);
    }
}
