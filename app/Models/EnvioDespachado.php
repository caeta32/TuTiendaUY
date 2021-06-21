<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvioDespachado extends Model
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
        return $this->hasOne(Envio::class, 'envio_despachados_idenvio_foreign', 'idEnvio');
    }
}
