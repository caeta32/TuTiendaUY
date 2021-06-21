<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmacion extends Model
{
    public $table = "confirmacion";
    use HasFactory;
    protected $fillable = [
        'email',
        'codigo',
    ];
}
