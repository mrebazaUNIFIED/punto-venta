<?php

namespace App\Exports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventarioExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Inventario::with('articulo')->get()->map(function ($inv) {
            return [
                $inv->articulo->codigo,
                $inv->articulo->nombre,
                $inv->stock,
                $inv->articulo->p_compra,
                $inv->articulo->p_venta
            ];
        });
    }

    public function headings(): array
    {
        return ["Código", "Producto", "Stock", "Precio Compra", "Precio Venta"];
    }
}
