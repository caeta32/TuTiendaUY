<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosContienenProdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_contienen_prods', function (Blueprint $table) {
            $table->unsignedBigInteger('idPedido');
            $table->string('codigoProducto', 100);
            $table->integer('cantidadPedida');
            // Primary Key compuesta.
            $table->primary(['idPedido','codigoProducto']);
            $table->timestamps();

            $table->foreign('codigoProducto')->references('codigo')->on('productos')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('idPedido')->references('id')->on('pedidos')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos_contienen_prods');
    }
}
