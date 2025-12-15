<?php

namespace Database\Seeders;

use App\Models\TipoPagoPedido;
use Illuminate\Database\Seeder;

class TipoPagoPedidoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'idTipoPagoPedido' => 1,
                'descripcionTipoPagoPedido' => 'Efectivo',
            ],
            [
                'idTipoPagoPedido' => 2,
                'descripcionTipoPagoPedido' => 'Tarjeta',
            ],
            [
                'idTipoPagoPedido' => 3,
                'descripcionTipoPagoPedido' => 'Yape',
            ],
            [
                'idTipoPagoPedido' => 4,
                'descripcionTipoPagoPedido' => 'Plin',
            ],
            [
                'idTipoPagoPedido' => 5,
                'descripcionTipoPagoPedido' => 'Transferencia',
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoPagoPedido::updateOrCreate(
                ['idTipoPagoPedido' => $tipo['idTipoPagoPedido']],
                [
                    'descripcionTipoPagoPedido' => $tipo['descripcionTipoPagoPedido'],
                    'estadoDB' => 1,
                ]
            );
        }

        $this->command->info('Tipos de pago creados correctamente.');
    }
}
