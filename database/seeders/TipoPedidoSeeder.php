<?php

namespace Database\Seeders;

use App\Models\TipoPedido;
use Illuminate\Database\Seeder;

class TipoPedidoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'idTipoPedido' => 1,
                'descripcionTipoPedido' => 'Salón',
            ],
            [
                'idTipoPedido' => 2,
                'descripcionTipoPedido' => 'Delivery',
            ],
            [
                'idTipoPedido' => 3,
                'descripcionTipoPedido' => 'Para Llevar',
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoPedido::updateOrCreate(
                ['idTipoPedido' => $tipo['idTipoPedido']],
                [
                    'descripcionTipoPedido' => $tipo['descripcionTipoPedido'],
                    'estadoDB' => 1,
                ]
            );
        }

        $this->command->info('Tipos de pedido creados correctamente.');
    }
}
