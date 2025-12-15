<?php

namespace Database\Seeders;

use App\Models\EstadoMesa;
use Illuminate\Database\Seeder;

class EstadoMesaSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['idEstadoMesa' => 1, 'descripcionEstadoMesa' => 'Libre'],
            ['idEstadoMesa' => 2, 'descripcionEstadoMesa' => 'Ocupada'],
            ['idEstadoMesa' => 3, 'descripcionEstadoMesa' => 'Reservada'],
            ['idEstadoMesa' => 4, 'descripcionEstadoMesa' => 'Mantenimiento'],
        ];

        foreach ($estados as $estado) {
            EstadoMesa::updateOrCreate(
                ['idEstadoMesa' => $estado['idEstadoMesa']],
                [
                    'descripcionEstadoMesa' => $estado['descripcionEstadoMesa'],
                    'estadoDB' => 1,
                ]
            );
        }

        $this->command->info('Estados de mesa creados correctamente.');
    }
}
