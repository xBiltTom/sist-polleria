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
        Schema::table('pedido', function (Blueprint $table) {
            $table->integer('idCliente')->nullable()->after('idAgentePedidos');
            $table->foreign('idCliente')
                  ->references('idCliente')
                  ->on('clientes_registrados')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign(['idCliente']);
            $table->dropColumn('idCliente');
        });
    }
};
