<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    //
    protected $table = 'compras';

    protected $fillable = ['folio', 'total', 'estado', 'proveedor_id'];

    public function detalle()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class); 
    }
}
