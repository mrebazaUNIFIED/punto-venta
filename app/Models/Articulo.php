<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Articulo extends Model
{
    use HasFactory;

    protected $table = 'articulos';

    protected $fillable = [
        'nombre',
        'codigo',
        'imagen',
        'estado',
        'p_venta',
        'p_compra',
        'categoria_id',

    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }
}
