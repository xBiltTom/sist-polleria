<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PedidosSeeder extends Seeder
{
    /**
     * Seeder maestro para inicializar todas las tablas necesarias del módulo de pedidos
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando seeders del módulo de Pedidos...');
        $this->command->newLine();

        // Estados de Mesa
        $this->command->info('📋 Creando estados de mesa...');
        $this->call(EstadoMesaSeeder::class);

        // Estados de Pedido
        $this->command->info('📋 Creando estados de pedido...');
        $this->call(EstadoPedidoSeeder::class);

        // Tipos de Pedido
        $this->command->info('📋 Creando tipos de pedido...');
        $this->call(TipoPedidoSeeder::class);

        // Modalidades de Pago
        $this->command->info('📋 Creando modalidades de pago...');
        $this->call(ModalidadPagoPedidoSeeder::class);

        // Tipos de Pago
        $this->command->info('📋 Creando tipos de pago...');
        $this->call(TipoPagoPedidoSeeder::class);

        // Tipos de Cliente
        $this->command->info('📋 Creando tipos de cliente...');
        $this->call(TipoClienteSeeder::class);

        $this->command->newLine();
        $this->command->info('✅ Todos los seeders del módulo de Pedidos se ejecutaron correctamente!');
        $this->command->newLine();

        // Resumen
        $this->command->table(
            ['Tabla', 'Registros'],
            [
                ['EstadoMesa', \App\Models\EstadoMesa::count()],
                ['EstadoPedido', \App\Models\EstadoPedido::count()],
                ['TipoPedido', \App\Models\TipoPedido::count()],
                ['ModalidadPagoPedido', \App\Models\ModalidadPagoPedido::count()],
                ['TipoPagoPedido', \App\Models\TipoPagoPedido::count()],
                ['TipoCliente', \App\Models\TipoCliente::count()],
            ]
        );
    }
}
