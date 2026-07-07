<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\{ArticuloController, UsuariosController, CajaController, CategoriaController, InventarioController, CompraController, DetalleCompraController, VentaController, DetalleVentaController, EmpresaController, PanelController, ProveedorController};
use App\Models\DetalleCompra;
use Illuminate\Support\Facades\Route;


//Rutas de autenticacion y profile
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [EmpresaController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

//Rutas del proyecto

//Panel de control
Route::prefix('panel')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('panel.usuarios.index');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{user}', [UsuariosController::class, 'update'])->name('usuarios.update');
    Route::put('/usuarios/{user}/password', [UsuariosController::class, 'password'])->name('usuarios.password');
    Route::delete('/usuarios/{user}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/roles', [UsuariosController::class, 'roles'])->name('panel.usuarios.roles');
});

//Caja
Route::prefix('caja')->middleware('auth')->group(function () {
    Route::get('/apertura', [CajaController::class, 'apertura'])->name('caja.apertura');
    Route::post('/apertura', [CajaController::class, 'store'])->name('caja.apertura');
    Route::get('/cierre', [CajaController::class, 'cierre'])->name('caja.cierre');
    Route::put('/{caja}', [CajaController::class, 'update'])->name('caja.update');
    Route::get('/historico', [CajaController::class, 'historico'])->name('caja.historico');
    Route::get('/{caja}', [CajaController::class, 'show'])->name('caja.show');
});
//Almacen
Route::prefix('almacen')->middleware('auth')->group(function () {
    //Vistas articulos
    Route::get('/articulos', [ArticuloController::class, 'index'])->name('almacen.articulo.index');
    Route::post('/articulos', [ArticuloController::class, 'store'])->name('almacen.articulo.store');
    Route::put('/articulos/{articulo}', [ArticuloController::class, 'update'])->name('almacen.articulo.update');
    Route::delete('/articulos/{articulo}', [ArticuloController::class, 'destroy'])->name('almacen.articulo.destroy');

    //Vistas Categorias
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('almacen.categoria.index');
    Route::resource('categorias', CategoriaController::class)
        ->except(['index'])
        ->names([
            'create' => 'almacen.categorias.create',
            'store' => 'almacen.categorias.store',
            'show' => 'almacen.categorias.show',
            'edit' => 'almacen.categorias.edit',
            'update' => 'almacen.categorias.update',
            'destroy' => 'almacen.categorias.destroy',
        ]);
    Route::get('/almacen/categorias', [CategoriaController::class, 'getCategorias'])->name('almacen.categorias.get');

    //Vistas inventario
    Route::resource('inventario', InventarioController::class)->only(['index', 'update'])->names(
        [
            'index' => 'almacen.inventario.index',
            'update' => 'almacen.inventario.update'
        ]
    );

    Route::get('/inventario/export/excel', [InventarioController::class, 'exportExcel'])->name('almacen.inventario.excel');
    Route::get('/inventario/export/pdf', [InventarioController::class, 'exportPdf'])->name('almacen.inventario.pdf');
});


//Compras
Route::prefix('compras')->middleware('auth')->group(function () {
    //Entrada
    Route::get('/entrada-productos', [CompraController::class, 'index'])->name('compras.entrada.index');
    Route::post('/compras/entrada', [CompraController::class, 'store'])->name('compras.entrada.store');
    Route::get('/search-articulos', [CompraController::class, 'searchArticulos'])->name('compras.search.articulos');


    //Detalles
    Route::get('/detalle-productos', [DetalleCompraController::class, 'index'])->name('compras.detalle.index');
    Route::get('/detalle-productos/{id}', [DetalleCompraController::class, 'show'])->name('compras.detalle.show');
    //Proveedores
    Route::get('/proveedores', [ProveedorController::class, 'index'])->name('compras.proveedor.index');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('compras.proveedor.store');
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('compras.proveedor.update');
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('compras.proveedor.destroy');
});

//Ventas
Route::prefix('ventas')->middleware('auth')->group(function () {
    Route::get('/posventa', [VentaController::class, 'index'])->name('ventas.posventa.index');
    Route::get('/create', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('/', [VentaController::class, 'store'])->name('ventas.store');


    Route::get('/search-articulos', [VentaController::class, 'searchArticulos'])->name('ventas.search.articulos');
    Route::get('/detalles', [DetalleVentaController::class, 'index'])->name('ventas.detalle.index');
    Route::get('/detalles/{detalleVenta}', [DetalleVentaController::class, 'show'])->name('ventas.detalle.show');

    Route::get('/{venta}', [VentaController::class, 'show'])->name('ventas.show');
});


//Reportes
Route::prefix('reportes')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/graficos', [EmpresaController::class, 'reportes'])->name('empresa.reportes');
});

//Configuracion
Route::prefix('configuracion')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/empresa', [EmpresaController::class, 'index'])->name('configuracion.empresa.index');
    Route::put('/empresa/{empresa}', [EmpresaController::class, 'update'])->name('configuracion.empresa.update');
});
