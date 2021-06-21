<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;

    // Convenciones de primary key
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    // Relación con tabla usuario.
    public function datosUsuario(){
        //belongsTo(Modelo, 'nombre de fk', 'nombre de la pk local')
        return $this->hasOne(Usuario::class, 'administradors_email_foreign', 'email');
    }
}
