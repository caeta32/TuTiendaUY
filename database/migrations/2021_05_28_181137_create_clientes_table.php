<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('contrasenia');
            $table->string('apellido', 50);
            $table->string('nombre', 50);
            $table->tinyInteger('confirmado');
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->string('nombre');
            $table->string('apellido');
            $table->string('fecha');
            $table->string('email')->primary();
            $table->string('telefono');
            $table->string('direccion');
            $table->string('postal');
            $table->string('pass');
            $table->tinyInteger('confirmado');
            $table->timestamps();

            $table->foreign('email')->references('email')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('administradors', function (Blueprint $table) {
            $table->string('email')->primary();

            $table->foreign('email')->references('email')->on('usuarios')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('administradors');
        Schema::dropIfExists('usuarios');
    }
}
