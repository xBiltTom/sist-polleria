<?php

namespace Database\Seeders;

use App\Models\EstadoPedido;
use Illuminate\Database\Seeder;

class EstadoPedidoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['idEstadoPedido' => 1, 'descripcionEstadoPedido' => 'Pendiente', 'estadoDB' => 1],
            ['idEstadoPedido' => 2, 'descripcionEstadoPedido' => 'En Preparación', 'estadoDB' => 1],
            ['idEstadoPedido' => 3, 'descripcionEstadoPedido' => 'Terminado', 'estadoDB' => 1],
            ['idEstadoPedido' => 4, 'descripcionEstadoPedido' => 'Entregado a Mozo', 'estadoDB' => 1],
            ['idEstadoPedido' => 5, 'descripcionEstadoPedido' => 'Entregado a Comensales', 'estadoDB' => 1],
            ['idEstadoPedido' => 6, 'descripcionEstadoPedido' => 'Por Cobrar', 'estadoDB' => 1],
            ['idEstadoPedido' => 7, 'descripcionEstadoPedido' => 'Cobrado', 'estadoDB' => 1],
            ['idEstadoPedido' => 8, 'descripcionEstadoPedido' => 'Cancelado', 'estadoDB' => 1],
        ];

        foreach ($estados as $estado) {
            EstadoPedido::updateOrCreate(
                ['idEstadoPedido' => $estado['idEstadoPedido']],
                $estado
            );
        }
    }
}
