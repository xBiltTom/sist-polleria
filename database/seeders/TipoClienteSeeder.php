<?php

namespace Database\Seeders;

use App\Models\TipoCliente;
use Illuminate\Database\Seeder;

class TipoClienteSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            [
                'idTipoCliente' => 1,
                'descripcionTipoCliente' => 'Natural',
            ],
            [
                'idTipoCliente' => 2,
                'descripcionTipoCliente' => 'Jurídica',
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoCliente::updateOrCreate(
                ['idTipoCliente' => $tipo['idTipoCliente']],
                [
                    'descripcionTipoCliente' => $tipo['descripcionTipoCliente'],
                    'estadoDB' => 1,
                ]
            );
        }

        $this->command->info('Tipos de cliente creados correctamente.');
    }
}
