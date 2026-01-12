<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->integer('idAgentePedidos')->nullable()->after('idMozo');
            $table->index('idAgentePedidos');
        });
        
        // Agregar foreign key en statement separado
        DB::statement('ALTER TABLE pedido ADD CONSTRAINT pedido_idagentepedidos_foreign FOREIGN KEY (idAgentePedidos) REFERENCES empleado(idEmpleado) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign('pedido_idagentepedidos_foreign');
            $table->dropIndex(['idAgentePedidos']);
            $table->dropColumn('idAgentePedidos');
        });
    }
};
