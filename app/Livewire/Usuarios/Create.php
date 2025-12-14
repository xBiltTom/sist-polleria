<?php

namespace App\Livewire\Usuarios;

use App\Models\Empleado;
use App\Models\User;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use WithSweetAlert;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $idEmpleado = null;
    public ?int $idRole = null;

    protected $listeners = ['save'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'idEmpleado' => 'nullable|exists:empleado,idEmpleado',
            'idRole' => 'required|exists:roles,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'idRole.required' => 'Debe seleccionar un rol.',
        ];
    }

    public function getEmpleadosDisponiblesProperty()
    {
        // Obtener empleados que no tienen usuario asignado
        $empleadosConUsuario = User::whereNotNull('idEmpleado')->pluck('idEmpleado');

        return Empleado::whereNotIn('idEmpleado', $empleadosConUsuario)
            ->orderBy('nombreEmpleado')
            ->get();
    }

    public function getRolesProperty()
    {
        // Si no es super-admin, no mostrar ese rol
        if (!auth()->user()->hasRole('super-admin')) {
            return Role::where('name', '!=', 'super-admin')->get();
        }
        return Role::all();
    }

    public function confirmSave(): void
    {
        $this->validate();

        $this->confirmAlert(
            title: '¿Crear usuario?',
            text: "Se creará el usuario '{$this->name}'.",
            confirmButtonText: 'Sí, crear',
            method: 'save'
        );
    }

    public function save(): void
    {
        $validated = $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'idEmpleado' => $this->idEmpleado,
        ]);

        // Asignar rol
        $role = Role::find($this->idRole);
        if ($role) {
            $user->assignRole($role);
        }

        session()->flash('swal', [
            'title' => '¡Creado!',
            'text' => 'El usuario se ha creado correctamente.',
            'icon' => 'success',
        ]);

        $this->redirect(route('usuarios.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.usuarios.create', [
            'empleadosDisponibles' => $this->empleadosDisponibles,
            'roles' => $this->roles,
        ])->layout('layouts.dashboard');
    }
}
