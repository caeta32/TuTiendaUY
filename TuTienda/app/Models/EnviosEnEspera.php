<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnviosEnEspera extends Model
{
    use HasFactory;

    protected $fillable = [
        'idEnvio'
    ];

    // Convenciones de primary key
    protected $primaryKey = 'idEnvio';
    public $incrementing = false;

    // Relación con envio.
    public function envio(){
        return $this->hasOne(Envio::class, 'envios_en_esperas_idenvio_foreign', 'idEnvio');
    }
}
