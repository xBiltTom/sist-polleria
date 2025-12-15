<?php

namespace Database\Seeders;

use App\Models\ModalidadPagoPedido;
use Illuminate\Database\Seeder;

class ModalidadPagoPedidoSeeder extends Seeder
{
    public function run(): void
    {
        $modalidades = [
            [
                'idModalidadPagoPedido' => 1,
                'nombreModalidadPagoPedido' => 'Total',
                'descripcionModalidadPagoPedido' => 'Pago único',
            ],
            [
                'idModalidadPagoPedido' => 2,
                'nombreModalidadPagoPedido' => 'Dividida',
                'descripcionModalidadPagoPedido' => 'Pago dividido',
            ],
        ];

        foreach ($modalidades as $modalidad) {
            ModalidadPagoPedido::updateOrCreate(
                ['idModalidadPagoPedido' => $modalidad['idModalidadPagoPedido']],
                [
                    'nombreModalidadPagoPedido' => $modalidad['nombreModalidadPagoPedido'],
                    'descripcionModalidadPagoPedido' => $modalidad['descripcionModalidadPagoPedido'],
                    'estadoDB' => 1,
                ]
            );
        }

        $this->command->info('Modalidades de pago creadas correctamente.');
    }
}
