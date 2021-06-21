<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    // Convenciones para Primary Key.
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
}
