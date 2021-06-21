<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidosContienenProd extends Model
{
    use HasFactory;

    protected $fillable = [
        'idPedido',
        'codigoProducto',
        'cantidadPedida',
    ];

    // Convenciones de Primary Key
    // Se eligió una de las dos pk que componen la pk compuesta
    // simplemente para cumplir con la convención, ya que eloquent no
    // admite pk compuesta.
    protected $primaryKey = 'idPedido';
    public $incrementing = false;

    // Relación con pedidos
    public function pedidos() {
        //belongsTo(Modelo, 'nombre de fk', 'nombre de columna referenciada')
        return $this->belongsTo(Pedidos::class, 'pedidos_contienen_prods_idpedido_foreign', 'id');
    }

    //Relación con productos
    public function productos() {
        //belongsTo(Modelo, 'nombre de fk', 'nombre de columna referenciada')
        return $this->belongsTo(Productos::class, 'pedidos_contienen_prods_codigoproducto_foreign', 'codigo');
    }
}
