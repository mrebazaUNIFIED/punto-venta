<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $fillable = [
        'venta_id',
        'articulo_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}