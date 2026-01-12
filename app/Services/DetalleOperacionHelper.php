<?php

namespace App\Services;

use App\Models\DetalleOperacionAlmacen;
use App\Models\Producto;

class DetalleOperacionHelper
{
    /**
     * Determina el tipo de operación de un detalle específico
     * extrayendo el tipo del campo nombreProducto
     */
    public static function determinarTipoOperacion(DetalleOperacionAlmacen $detalle): array
    {
        // Extraer el tipo del nombre del producto [Tipo:X]
        if (preg_match('/\[Tipo:(\d+)\]/', $detalle->nombreProducto, $matches)) {
            $tipo = (int)$matches[1];

            $tiposInfo = [
                1 => [
                    'tipo' => 1,
                    'badge' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300',
                    'texto' => 'Creación',
                    'icono' => '✨'
                ],
                2 => [
                    'tipo' => 2,
                    'badge' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300',
                    'texto' => 'Adición',
                    'icono' => '➕'
                ],
                3 => [
                    'tipo' => 3,
                    'badge' => 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300',
                    'texto' => 'Sustracción',
                    'icono' => '➖'
                ],
                4 => [
                    'tipo' => 4,
                    'badge' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300',
                    'texto' => 'Eliminación',
                    'icono' => '🗑️'
                ]
            ];

            return $tiposInfo[$tipo] ?? $tiposInfo[1];
        }

        // Fallback: usar lógica anterior si no hay metadata
        $producto = Producto::find($detalle->idProducto);

        if (!$producto || !$producto->estadoDB) {
            return [
                'tipo' => 4,
                'badge' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300',
                'texto' => 'Eliminación',
                'icono' => '🗑️'
            ];
        }

        // Por defecto, creación
        return [
            'tipo' => 1,
            'badge' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300',
            'texto' => 'Creación',
            'icono' => '✨'
        ];
    }

    /**
     * Obtiene el nombre del producto limpio sin la metadata del tipo
     */
    public static function obtenerNombreLimpio(string $nombreProducto): string
    {
        return preg_replace('/\s*\[Tipo:\d+\]\s*$/', '', $nombreProducto);
    }
}
