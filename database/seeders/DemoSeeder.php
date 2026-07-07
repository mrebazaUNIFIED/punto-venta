<?php

namespace Database\Seeders;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Datos de demostración: categorías y productos con stock inicial.
     * Es idempotente, se puede ejecutar varias veces sin duplicar registros.
     */
    public function run(): void
    {
        $data = [
            'Bebidas' => [
                'descripcion' => 'Gaseosas, aguas y jugos',
                'articulos' => [
                    ['nombre' => 'Coca Cola 500ml', 'codigo' => 'BEB-001', 'p_compra' => 2.50, 'p_venta' => 3.50, 'stock' => 24],
                    ['nombre' => 'Agua San Luis 625ml', 'codigo' => 'BEB-002', 'p_compra' => 1.00, 'p_venta' => 1.50, 'stock' => 36],
                ],
            ],
            'Abarrotes' => [
                'descripcion' => 'Productos de primera necesidad',
                'articulos' => [
                    ['nombre' => 'Arroz Costeño 1kg', 'codigo' => 'ABA-001', 'p_compra' => 3.80, 'p_venta' => 4.50, 'stock' => 50],
                    ['nombre' => 'Aceite Primor 1L', 'codigo' => 'ABA-002', 'p_compra' => 8.50, 'p_venta' => 10.00, 'stock' => 20],
                ],
            ],
            'Snacks' => [
                'descripcion' => 'Golosinas y piqueos',
                'articulos' => [
                    ['nombre' => 'Papas Lays Clásicas', 'codigo' => 'SNK-001', 'p_compra' => 1.80, 'p_venta' => 2.50, 'stock' => 30],
                ],
            ],
        ];

        foreach ($data as $catNombre => $info) {
            $categoria = Categoria::firstOrCreate(
                ['nombre' => $catNombre],
                ['descripcion' => $info['descripcion']]
            );

            foreach ($info['articulos'] as $art) {
                $articulo = Articulo::firstOrCreate(
                    ['codigo' => $art['codigo']],
                    [
                        'nombre' => $art['nombre'],
                        'categoria_id' => $categoria->id,
                        'p_compra' => $art['p_compra'],
                        'p_venta' => $art['p_venta'],
                        'estado' => 1,
                    ]
                );

                Inventario::firstOrCreate(
                    ['articulo_id' => $articulo->id],
                    ['stock' => $art['stock']]
                );
            }
        }
    }
}
