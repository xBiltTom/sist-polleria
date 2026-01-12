<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AgentePedidos;

Route::middleware(['auth:web'])->prefix('agente-pedidos')->name('agente-pedidos.')->group(function () {
    Route::get('/mis-pedidos', AgentePedidos\MisPedidos::class)->name('mis-pedidos');
});
