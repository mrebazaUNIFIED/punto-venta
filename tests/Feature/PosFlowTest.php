<?php

use App\Models\Articulo;
use App\Models\Caja;
use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\User;
use App\Models\Venta;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'vendedor']);

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
});

test('se puede crear una categoría', function () {
    $response = $this->actingAs($this->user)->post(route('almacen.categorias.store'), [
        'nombre' => 'Bebidas',
        'descripcion' => 'Gaseosas y jugos',
    ]);

    $response->assertRedirect(route('almacen.categoria.index'));
    $this->assertDatabaseHas('categorias', ['nombre' => 'Bebidas']);
});

test('se puede registrar un artículo con stock inicial', function () {
    $categoria = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Gaseosas']);

    $response = $this->actingAs($this->user)->post(route('almacen.articulo.store'), [
        'nombre' => 'Coca Cola 500ml',
        'codigo' => 'BEV-001',
        'categoria_id' => $categoria->id,
        'p_compra' => 2.50,
        'p_venta' => 3.50,
        'stock' => 25,
    ]);

    $response->assertRedirect(route('almacen.articulo.index'));
    $articulo = Articulo::where('codigo', 'BEV-001')->first();
    expect($articulo)->not->toBeNull();
    expect($articulo->inventario->stock)->toBe(25);
});

test('se puede editar un artículo enviando la categoría', function () {
    $categoria = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Gaseosas']);
    $articulo = Articulo::create([
        'nombre' => 'Inka Cola',
        'codigo' => 'BEV-002',
        'categoria_id' => $categoria->id,
        'p_compra' => 2,
        'p_venta' => 3,
        'estado' => 1,
    ]);

    $response = $this->actingAs($this->user)->put(route('almacen.articulo.update', $articulo), [
        'nombre' => 'Inka Cola 1L',
        'codigo' => 'BEV-002',
        'categoria_id' => $categoria->id,
        'p_compra' => 3.00,
        'p_venta' => 4.50,
    ]);

    $response->assertOk()->assertJson(['success' => true]);
    $this->assertDatabaseHas('articulos', ['id' => $articulo->id, 'nombre' => 'Inka Cola 1L']);
});

test('la venta descuenta stock y acumula en la caja', function () {
    $categoria = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Gaseosas']);
    $articulo = Articulo::create([
        'nombre' => 'Agua San Luis',
        'codigo' => 'BEV-003',
        'categoria_id' => $categoria->id,
        'p_compra' => 1,
        'p_venta' => 2,
        'estado' => 1,
    ]);
    Inventario::create(['articulo_id' => $articulo->id, 'stock' => 10]);

    $caja = Caja::create([
        'user_id' => $this->user->id,
        'monto_apertura' => 100,
        'fecha_apertura' => now(),
        'estado' => true,
    ]);

    $response = $this->actingAs($this->user)->post(route('ventas.store'), [
        'codigo' => 'V-TEST-1',
        'tipo_pago' => 'efectivo',
        'detalles' => [
            ['articulo_id' => $articulo->id, 'cantidad' => 4],
        ],
    ]);

    $response->assertRedirect(route('ventas.posventa.index'));
    expect($articulo->inventario->fresh()->stock)->toBe(6);
    expect((float) Venta::first()->total)->toBe(8.0);
    expect((float) $caja->fresh()->monto_cierre)->toBe(108.0);
});

test('la venta se rechaza si no hay stock suficiente', function () {
    $categoria = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Gaseosas']);
    $articulo = Articulo::create([
        'nombre' => 'Sprite',
        'codigo' => 'BEV-004',
        'categoria_id' => $categoria->id,
        'p_compra' => 1,
        'p_venta' => 2,
        'estado' => 1,
    ]);
    Inventario::create(['articulo_id' => $articulo->id, 'stock' => 3]);

    Caja::create([
        'user_id' => $this->user->id,
        'monto_apertura' => 50,
        'fecha_apertura' => now(),
        'estado' => true,
    ]);

    $response = $this->actingAs($this->user)->from(route('ventas.posventa.index'))->post(route('ventas.store'), [
        'codigo' => 'V-TEST-2',
        'tipo_pago' => 'efectivo',
        'detalles' => [
            ['articulo_id' => $articulo->id, 'cantidad' => 5],
        ],
    ]);

    $response->assertSessionHas('error');
    expect($articulo->inventario->fresh()->stock)->toBe(3);
    expect(Venta::count())->toBe(0);
});

test('sin caja abierta la venta redirige a apertura', function () {
    $categoria = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Gaseosas']);
    $articulo = Articulo::create([
        'nombre' => 'Fanta',
        'codigo' => 'BEV-005',
        'categoria_id' => $categoria->id,
        'p_compra' => 1,
        'p_venta' => 2,
        'estado' => 1,
    ]);
    Inventario::create(['articulo_id' => $articulo->id, 'stock' => 5]);

    $response = $this->actingAs($this->user)->post(route('ventas.store'), [
        'codigo' => 'V-TEST-3',
        'tipo_pago' => 'efectivo',
        'detalles' => [
            ['articulo_id' => $articulo->id, 'cantidad' => 1],
        ],
    ]);

    $response->assertRedirect(route('caja.apertura'));
    expect(Venta::count())->toBe(0);
});

test('la compra de entrada incrementa el stock', function () {
    $categoria = Categoria::create(['nombre' => 'Bebidas', 'descripcion' => 'Gaseosas']);
    $articulo = Articulo::create([
        'nombre' => 'Pepsi',
        'codigo' => 'BEV-006',
        'categoria_id' => $categoria->id,
        'p_compra' => 1.5,
        'p_venta' => 2.5,
        'estado' => 1,
    ]);
    Inventario::create(['articulo_id' => $articulo->id, 'stock' => 2]);

    $proveedor = \App\Models\Proveedor::create([
        'nombre' => 'Distribuidora Lima',
        'ruc' => '20123456789',
        'direccion' => 'Av. Test 123',
        'telefono' => '999888777',
    ]);

    $response = $this->actingAs($this->user)->post(route('compras.entrada.store'), [
        'folio' => 'F-TEST-1',
        'proveedor_id' => $proveedor->id,
        'detalles' => [
            ['articulo_id' => $articulo->id, 'cantidad' => 12, 'precio' => 1.5],
        ],
    ]);

    $response->assertSessionHas('success');
    expect($articulo->inventario->fresh()->stock)->toBe(14);
    $this->assertDatabaseHas('compras', ['folio' => 'F-TEST-1', 'total' => 18]);
});

test('abrir y cerrar caja funciona', function () {
    $this->actingAs($this->user)->post(route('caja.apertura'), ['monto_apertura' => 200])
        ->assertSessionHas('success');

    $caja = Caja::where('user_id', $this->user->id)->whereNull('fecha_cierre')->first();
    expect($caja)->not->toBeNull();

    $this->actingAs($this->user)->put(route('caja.update', $caja))
        ->assertSessionHas('success');

    expect($caja->fresh()->estado)->toBeFalse();
    expect($caja->fresh()->fecha_cierre)->not->toBeNull();
});

test('el registro de usuario asigna rol vendedor', function () {
    $response = $this->post(route('register'), [
        'name' => 'Nuevo Vendedor',
        'email' => 'vendedor@test.com',
        'username' => 'vendedor1',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $user = User::where('email', 'vendedor@test.com')->first();
    expect($user)->not->toBeNull();
    expect($user->hasRole('vendedor'))->toBeTrue();
});
