<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabla para los telefonos de cada cliente.

class CreateTelefonosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telefonos_clientes', function (Blueprint $table) {
            $table->string('email');
            $table->string('telefono', 30);
            $table->timestamps();
            // Primary Key Compuesta.
            $table->primary(['email', 'telefono']);

            $table->foreign('email')->references('email')->on('clientes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telefonos_clientes');
    }
}
