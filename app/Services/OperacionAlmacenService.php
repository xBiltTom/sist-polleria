<?php

namespace App\Services;

use App\Models\OperacionAlmacen;
use App\Models\DetalleOperacionAlmacen;
use App\Models\Producto;
use Carbon\Carbon;

class OperacionAlmacenService
{
    /**
     * Obtiene o crea la operación de almacén del día actual (GMT-5)
     * Todas las operaciones del mismo día comparten el mismo registro
     */
    private function getOrCreateOperacionDelDia(): OperacionAlmacen
    {
        $hoy = Carbon::now('America/Lima')->startOfDay();

        $operacion = OperacionAlmacen::whereDate('fechaOperacionAlmacen', $hoy)->first();

        if (!$operacion) {
            $operacion = OperacionAlmacen::create([
                'idTipoOperacionAlmacen' => 1, // Valor inicial, se actualiza con cada operación
                'idJefeAlmacen' => auth()->user()->idEmpleado ?? null,
                'fechaOperacionAlmacen' => $hoy
            ]);
        }

        return $operacion;
    }

    /**
     * Crea un detalle de operación con metadata del tipo
     */
    private function crearDetalle(OperacionAlmacen $operacion, Producto $producto, int $cantidad, int $tipoOperacion): void
    {
        DetalleOperacionAlmacen::create([
            'idOperacionAlmacen' => $operacion->idOperacionAlmacen,
            'idProducto' => $producto->idProducto,
            'cantidadProducto' => $cantidad,
            'nombreProducto' => $producto->nombreProducto . ' [Tipo:' . $tipoOperacion . ']', // Agregamos el tipo al nombre temporalmente
            'fechaDetalleOperacion' => Carbon::now('America/Lima')
        ]);
    }

    /**
     * Registra una creación de producto
     * Tipo de operación: 1 (Creación)
     */
    public function registrarCreacion(Producto $producto): void
    {
        $operacion = $this->getOrCreateOperacionDelDia();
        $this->crearDetalle($operacion, $producto, $producto->stockProducto, 1);
    }

    /**
     * Registra una adición de stock (incremento)
     * Tipo de operación: 2 (Adición)
     */
    public function registrarAdicion(Producto $producto, int $stockAnterior, int $stockNuevo): void
    {
        if ($stockNuevo <= $stockAnterior) {
            return; // No es una adición
        }

        $operacion = $this->getOrCreateOperacionDelDia();
        $cantidadAdicionada = $stockNuevo - $stockAnterior;
        $this->crearDetalle($operacion, $producto, $cantidadAdicionada, 2);
    }

    /**
     * Registra una sustracción de stock (decremento)
     * Tipo de operación: 3 (Sustracción)
     */
    public function registrarSustraccion(Producto $producto, int $stockAnterior, int $stockNuevo): void
    {
        if ($stockNuevo >= $stockAnterior) {
            return; // No es una sustracción
        }

        $operacion = $this->getOrCreateOperacionDelDia();
        $cantidadSustraida = $stockAnterior - $stockNuevo;
        $this->crearDetalle($operacion, $producto, $cantidadSustraida, 3);
    }

    /**
     * Registra una eliminación de producto
     * Tipo de operación: 4 (Eliminación)
     */
    public function registrarEliminacion(Producto $producto): void
    {
        $operacion = $this->getOrCreateOperacionDelDia();
        $this->crearDetalle($operacion, $producto, $producto->stockProducto, 4);
    }

    /**
     * Registra cambios en el stock del producto
     * Detecta automáticamente si es adición o sustracción
     */
    public function registrarCambioStock(Producto $producto, int $stockAnterior): void
    {
        $stockNuevo = $producto->stockProducto;

        if ($stockNuevo > $stockAnterior) {
            $this->registrarAdicion($producto, $stockAnterior, $stockNuevo);
        } elseif ($stockNuevo < $stockAnterior) {
            $this->registrarSustraccion($producto, $stockAnterior, $stockNuevo);
        }
        // Si son iguales, no se registra nada
    }
}
