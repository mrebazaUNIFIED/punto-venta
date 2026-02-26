<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';

    protected $fillable = [
        'compra_id',
        'articulo_id',
        'cantidad',
        'precio',
        'subtotal',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}