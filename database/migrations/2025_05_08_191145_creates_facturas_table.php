<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('facturas', function (Blueprint $table) {
        $table->id();  // Clave primaria de la tabla facturas
        $table->integer('numero');
        $table->string('detalles');
        $table->integer('valor');
        $table->string('archivo');

        // Nuevas columnas
        $table->unsignedBigInteger('idcliente'); // Asegúrate de que sea unsignedBigInteger
        $table->unsignedBigInteger('idestado');
        $table->unsignedBigInteger('idforma');

        // Claves foráneas
        $table->foreign('idcliente')->references('id')->on('clientes')->onDelete('cascade');
        $table->foreign('idestado')->references('id')->on('estadosfacturas')->onDelete('cascade');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
