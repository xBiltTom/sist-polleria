<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Cajero;

Route::middleware(['auth:web'])->prefix('cajero')->name('cajero.')->group(function () {
    Route::get('/validar-pagos', Cajero\ValidarPagos::class)->name('validar-pagos');
});
