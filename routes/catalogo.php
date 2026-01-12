<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Catalogo\Index;
use App\Livewire\Catalogo\Checkout;

// Rutas públicas (sin autenticación)
Route::get('/catalogo', Index::class)->name('catalogo.index');
Route::get('/checkout', Checkout::class)->name('catalogo.checkout');
