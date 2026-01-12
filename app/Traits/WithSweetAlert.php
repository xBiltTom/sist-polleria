<?php

namespace App\Traits;

trait WithSweetAlert
{
    /**
     * Muestra una alerta de confirmación.
     */
    public function confirmAlert(string $title, string $text, string $confirmButtonText, string $method, array $params = []): void
    {
        $this->dispatch('swal:confirm', [
            'title' => $title,
            'text' => $text,
            'icon' => 'warning',
            'confirmButtonText' => $confirmButtonText,
            'cancelButtonText' => 'Cancelar',
            'method' => $method,
            'params' => $params,
        ]);
    }

    /**
     * Muestra una alerta de éxito.
     */
    public function successAlert(string $title, string $text = ''): void
    {
        $this->dispatch('swal:success', [
            'title' => $title,
            'text' => $text,
            'icon' => 'success',
        ]);
    }

    /**
     * Muestra una alerta de error.
     */
    public function errorAlert(string $title, string $text = ''): void
    {
        $this->dispatch('swal:error', [
            'title' => $title,
            'text' => $text,
            'icon' => 'error',
        ]);
    }

    /**
     * Muestra una alerta de información.
     */
    public function infoAlert(string $title, string $text = ''): void
    {
        $this->dispatch('swal:info', [
            'title' => $title,
            'text' => $text,
            'icon' => 'info',
        ]);
    }

    /**
     * Muestra una alerta de advertencia.
     */
    public function warningAlert(string $title, string $text = ''): void
    {
        $this->dispatch('swal:warning', [
            'title' => $title,
            'text' => $text,
            'icon' => 'warning',
        ]);
    }
}
