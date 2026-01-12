<?php

namespace App\Livewire\Usuarios;

use App\Models\Empleado;
use App\Models\User;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    use WithSweetAlert;

    public User $user;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $idEmpleado = null;
    public ?int $idRole = null;

    protected $listeners = ['save'];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name?? '';
        $this->email = $user->email?? '';
        $this->idEmpleado = $user->idEmpleado;
        $this->idRole = $user->roles->first()?->id;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|confirmed',
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
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'idRole.required' => 'Debe seleccionar un rol.',
        ];
    }

    public function getEmpleadosDisponiblesProperty()
    {
        // Obtener empleados que no tienen usuario asignado (excepto el actual)
        $empleadosConUsuario = User::whereNotNull('idEmpleado')
            ->where('id', '!=', $this->user->id)
            ->pluck('idEmpleado');

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
            title: '¿Actualizar usuario?',
            text: "Se actualizará el usuario '{$this->name}'.",
            confirmButtonText: 'Sí, actualizar',
            method: 'save'
        );
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Preparar datos para actualizar
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'idEmpleado' => $this->idEmpleado,
        ];

        // Solo actualizar contraseña si se proporcionó
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        // Actualizar rol
        $role = Role::find($this->idRole);
        if ($role) {
            $this->user->syncRoles([$role]);
        }

        session()->flash('swal', [
            'title' => '¡Actualizado!',
            'text' => 'El usuario se ha actualizado correctamente.',
            'icon' => 'success',
        ]);

        $this->redirect(route('usuarios.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.usuarios.edit', [
            'empleadosDisponibles' => $this->empleadosDisponibles,
            'roles' => $this->roles,
        ])->layout('layouts.dashboard');
    }
}
