<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insertar nuevos estados de pedido para e-commerce
        DB::table('estado_pedido')->insert([
            ['idEstadoPedido' => 8, 'descripcionEstadoPedido' => 'Pago Pendiente', 'estadoDB' => 1],
            ['idEstadoPedido' => 9, 'descripcionEstadoPedido' => 'Pago Validado', 'estadoDB' => 1],
            ['idEstadoPedido' => 10, 'descripcionEstadoPedido' => 'Pendiente de Envío', 'estadoDB' => 1],
            ['idEstadoPedido' => 11, 'descripcionEstadoPedido' => 'Enviado', 'estadoDB' => 1],
            ['idEstadoPedido' => 12, 'descripcionEstadoPedido' => 'Recibido', 'estadoDB' => 1],
        ]);

        // Insertar nuevo tipo de empleado: Agente de Pedidos
        DB::table('tipo_empleado')->insert([
            'idTipoEmpleado' => 6,
            'nombreTipoEmpleado' => 'Agente de Pedidos',
            'descripcionTipoEmpleado' => 'Encargado de entregar pedidos a clientes',
            'estadoDB' => 1
        ]);

        // Insertar nuevo tipo de pedido: Online
        DB::table('tipo_pedido')->insert([
            'idTipoPedido' => 4,
            'descripcionTipoPedido' => 'Online',
            'estadoDB' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('estado_pedido')->whereIn('idEstadoPedido', [8, 9, 10, 11, 12])->delete();
        DB::table('tipo_empleado')->where('idTipoEmpleado', 6)->delete();
        DB::table('tipo_pedido')->where('idTipoPedido', 4)->delete();
    }
};
