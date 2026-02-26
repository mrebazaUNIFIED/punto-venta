<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    //
    use HasFactory;

     protected $fillable = [
        'articulo_id',
        'stock'
        
    ];
 
    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

}
