<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'emailComprador',
        'idEnvio',
        'cantidadTotal',
        'precioTotal'
    ];

    // Relación con comprador.
    public function comprador(){
        //belongsTo(Modelo, 'nombre de fk', 'nombre de columna referenciada')
        return $this->belongsTo(Cliente::class, 'pedidos_emailcomprador_foreign', 'email');
    }
    // Relación con envío.
    public function envio(){
        return $this->hasOne(Envio::class, 'pedidos_idenvio_foreign', 'idEnvio');
    }
}
