<?php

namespace App\Livewire\Catalogo;

use App\Models\ClienteRegistrado;
use App\Models\TipoCliente;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Registro de Cliente')]
class Registro extends Component
{
    public $nombreCliente = '';
    public $apellidoCliente = '';
    public $emailCliente = '';
    public $password = '';
    public $password_confirmation = '';
    public $dniCliente = '';
    public $celularCliente = '';
    public $direccionCliente = '';

    protected $rules = [
        'nombreCliente' => 'required|string|min:2|max:100',
        'apellidoCliente' => 'required|string|min:2|max:100',
        'emailCliente' => 'required|email|unique:clientes_registrados,emailCliente',
        'password' => 'required|min:6|confirmed',
        'dniCliente' => 'required|digits:8|unique:clientes_registrados,dniCliente',
        'celularCliente' => 'required|digits:9',
        'direccionCliente' => 'required|string|min:10|max:255',
    ];

    protected $messages = [
        'nombreCliente.required' => 'El nombre es obligatorio',
        'apellidoCliente.required' => 'El apellido es obligatorio',
        'emailCliente.required' => 'El correo electrónico es obligatorio',
        'emailCliente.email' => 'Ingrese un correo electrónico válido',
        'emailCliente.unique' => 'Este correo ya está registrado',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'dniCliente.required' => 'El DNI es obligatorio',
        'dniCliente.digits' => 'El DNI debe tener 8 dígitos',
        'dniCliente.unique' => 'Este DNI ya está registrado',
        'celularCliente.required' => 'El celular es obligatorio',
        'celularCliente.digits' => 'El celular debe tener 9 dígitos',
        'direccionCliente.required' => 'La dirección es obligatoria',
        'direccionCliente.min' => 'La dirección debe tener al menos 10 caracteres',
    ];

    public function registrar()
    {
        $this->validate();

        try {
            $cliente = ClienteRegistrado::create([
                'nombreCliente' => $this->nombreCliente,
                'apellidoCliente' => $this->apellidoCliente,
                'emailCliente' => $this->emailCliente,
                'password' => Hash::make($this->password),
                'dniCliente' => $this->dniCliente,
                'celularCliente' => $this->celularCliente,
                'direccionCliente' => $this->direccionCliente,
                'idTipoCliente' => 1, // Tipo cliente "Natural" por defecto
                'estadoCliente' => 1,
                'estadoDB' => 1,
            ]);

            // Autenticar automáticamente
            auth('cliente')->login($cliente);

            session()->flash('success', '¡Registro exitoso! Bienvenido a Don Pollón');
            return redirect()->route('catalogo.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar. Por favor intente nuevamente.');
        }
    }

    public function render()
    {
        return view('livewire.catalogo.registro')->layout('layouts.catalogo');
    }
}
