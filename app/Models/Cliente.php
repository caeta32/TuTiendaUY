<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'apellido',
        'fecha',
        'email',
        'telefono',
        'direccion',
        'postal',
        'pass'
    ];

    // Convenciones de primary key
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    // Relación con tabla usuario.
    public function datosUsuario(){
        //belongsTo(Modelo, 'nombre de fk', 'nombre de la pk local')
        return $this->hasOne(Usuario::class, 'clientes_email_foreign', 'email');
    }
}
