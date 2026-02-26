<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'user_id',
        'caja_id',
        'total',
        'tipo_pago',
        'codigo',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}