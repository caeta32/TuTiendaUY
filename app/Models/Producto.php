<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'emailVendedor',
        'nombre',
        'descripcion',
        'precio',
        'cantidadDisponible',
        'rutaImagen',
        'categoria'
    ];

    // Convenciones de primary key
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    // Relación con vendedor.
    public function vendedor(){
        //belongsTo(Modelo, 'nombre de fk', 'nombre de columna referenciada')
        return $this->belongsTo(Cliente::class, 'productos_emailvendedor_foreign', 'email');
    }
}
