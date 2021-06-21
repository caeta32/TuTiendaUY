<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->string('codigo', 100)->primary();
            $table->string('emailVendedor')->index()->nullable();
            $table->string('nombre', 100);
            $table->string('descripcion', 600);
            $table->double('precio');
            $table->integer('cantidadDisponible');
            $table->string('rutaImagen', 100);
            $table->timestamps();

            $table->foreign('emailVendedor')->references('email')->on('clientes')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('envios', function (Blueprint $table) {
            $table->id(); // Crea un campo 'id' UNSIGNED BIGINT auto incremental y primary key.
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('emailComprador')->nullable()->index();
            $table->unsignedBigInteger('idEnvio')->nullable()->unique();
            $table->integer('cantidadTotal');
            $table->double('precioTotal');
            $table->timestamps();

            $table->foreign('emailComprador')->references('email')->on('clientes')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('idEnvio')->references('id')->on('envios')->onUpdate('cascade')->onDelete('set null');
        });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
        Schema::dropIfExists('pedidos');
    }
}
