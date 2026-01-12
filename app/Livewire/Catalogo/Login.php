<?php

namespace App\Livewire\Catalogo;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Iniciar Sesión')]
class Login extends Component
{
    public $emailCliente = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'emailCliente' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'emailCliente.required' => 'El correo electrónico es obligatorio',
        'emailCliente.email' => 'Ingrese un correo electrónico válido',
        'password.required' => 'La contraseña es obligatoria',
    ];

    public function login()
    {
        $this->validate();

        if (auth('cliente')->attempt(['emailCliente' => $this->emailCliente, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            session()->flash('success', '¡Bienvenido de nuevo!');
            
            // Redirigir al carrito si tiene productos, sino al catálogo
            if (session()->has('carrito') && count(session('carrito')) > 0) {
                return redirect()->route('catalogo.carrito');
            }
            
            return redirect()->route('catalogo.index');
        }

        $this->addError('emailCliente', 'Las credenciales no coinciden con nuestros registros.');
    }

    public function render()
    {
        return view('livewire.catalogo.login')->layout('layouts.catalogo');
    }
}
