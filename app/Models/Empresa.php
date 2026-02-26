<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'razon_social',
        'nombre_comercial',
        'ruc',
        'direccion_fiscal',
        'ubigeo',
        'departamento',
        'provincia',
        'distrito',
        'telefono',
        'correo',
        'logo',
    ];
}
