<?php

use App\Livewire\OrdenAbastecimiento\Index;
use App\Livewire\OrdenAbastecimiento\Create;
use App\Livewire\OrdenAbastecimiento\Show;
use App\Http\Controllers\OrdenAbastecimientoController;
use Illuminate\Support\Facades\Route;

Route::prefix('orden-abastecimiento')->name('orden-abastecimiento.')->group(function () {
    Route::get('/', Index::class)->name('index');
    Route::get('/crear', Create::class)->name('create');
    Route::get('/{id}', Show::class)->name('show');
    Route::get('/{id}/factura', [OrdenAbastecimientoController::class, 'generarFactura'])->name('factura');
});
