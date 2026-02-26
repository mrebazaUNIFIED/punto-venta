<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
  protected $fillable = [
    'user_id',
    'monto_apertura',
    'monto_cierre',
    'fecha_apertura',
    'fecha_cierre',
    'estado',
  ];

  protected $casts = [
    'monto_apertura' => 'decimal:2',
    'monto_cierre' => 'decimal:2',
    'fecha_apertura' => 'datetime',
    'fecha_cierre' => 'datetime',
    'estado' => 'boolean',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function getUsuarioNombreAttribute()
  {
    return $this->user ? $this->user->name : 'Desconocido';
  }

  public function ventas()
  {
    return $this->hasMany(Venta::class);
  }
}
