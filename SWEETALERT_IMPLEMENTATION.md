# Implementación de SweetAlert2 en el CRUD de Empleados

## 📁 Archivos Creados/Modificados

### 1. Trait Reutilizable
**Archivo:** `app/Traits/WithSweetAlert.php`

Trait que proporciona métodos helper para mostrar diferentes tipos de alertas:
- `confirmAlert()` - Alerta de confirmación con botones Aceptar/Cancelar
- `successAlert()` - Alerta de éxito
- `errorAlert()` - Alerta de error
- `infoAlert()` - Alerta de información
- `warningAlert()` - Alerta de advertencia

### 2. Configuración JavaScript
**Archivo:** `resources/js/app.js`

Configuración personalizada de SweetAlert2 con:
- Estilos adaptados al tema de la pollería (colores naranja/dorado)
- Soporte para modo oscuro
- Event listeners de Livewire para todos los tipos de alertas
- Configuración de botones personalizados

### 3. Componente Blade para Flash Messages
**Archivo:** `resources/views/components/swal-flash.blade.php`

Componente que muestra alertas desde session flash después de redirecciones.

### 4. Componentes Livewire Actualizados

#### Index.php
```php
protected $listeners = ['delete'];

public function confirmDelete(int $id): void
{
    $this->confirmAlert(
        title: '¿Eliminar empleado?',
        text: 'Esta acción no se puede deshacer...',
        confirmButtonText: 'Sí, eliminar',
        method: 'delete',
        params: ['id' => $id]
    );
}

public function delete(int $id): void
{
    // Elimina y muestra alerta de éxito
}
```

#### Create.php
```php
protected $listeners = ['save'];

public function confirmSave(): void
{
    $this->confirmAlert(
        title: '¿Guardar empleado?',
        text: 'Se registrará un nuevo empleado...',
        confirmButtonText: 'Sí, guardar',
        method: 'save'
    );
}

public function save(): void
{
    // Guarda y redirige con session flash
}
```

#### Edit.php
```php
protected $listeners = ['save'];

public function confirmSave(): void
{
    $this->confirmAlert(
        title: '¿Actualizar empleado?',
        text: 'Se actualizarán los datos...',
        confirmButtonText: 'Sí, actualizar',
        method: 'save'
    );
}

public function save(): void
{
    // Actualiza y redirige con session flash
}
```

### 5. Vistas Actualizadas

- `livewire/empleados/create.blade.php` - Form submit actualizado a `wire:submit.prevent="confirmSave"`
- `livewire/empleados/edit.blade.php` - Form submit actualizado a `wire:submit.prevent="confirmSave"`
- `layouts/dashboard.blade.php` - Agregado `<x-swal-flash />` antes de `</body>`

## 🎨 Colores Personalizados

Los botones de SweetAlert2 usan las clases de Tailwind del sistema:
- **Botón Confirmar:** `bg-polleria-500 hover:bg-polleria-600 dark:bg-polleria-dark-600`
- **Botón Cancelar:** `bg-white border-gray-300 dark:bg-polleria-dark-800`
- **Popup modo oscuro:** `dark:bg-polleria-dark-900`

## 🔄 Flujo de Trabajo

### Crear/Editar Empleado
1. Usuario llena el formulario
2. Click en "Guardar Empleado"
3. Se ejecuta `confirmSave()`
4. Muestra SweetAlert de confirmación
5. Si acepta → ejecuta `save()`
6. Guarda en BD
7. Redirige a index con session flash
8. Muestra SweetAlert de éxito

### Eliminar Empleado
1. Usuario click en botón eliminar
2. Se ejecuta `confirmDelete(id)`
3. Muestra SweetAlert de confirmación
4. Si acepta → dispara evento Livewire `delete`
5. Se ejecuta `delete(id)`
6. Elimina de BD
7. Muestra SweetAlert de éxito en la misma página

## 📦 Uso en Otros Módulos

Para implementar SweetAlert2 en otros CRUDs:

1. **Agregar trait al componente:**
```php
use App\Traits\WithSweetAlert;

class MiComponente extends Component
{
    use WithSweetAlert;
    protected $listeners = ['metodoDesdeSwal'];
}
```

2. **Crear método de confirmación:**
```php
public function confirmAction(): void
{
    $this->confirmAlert(
        title: 'Título',
        text: 'Descripción',
        confirmButtonText: 'Aceptar',
        method: 'metodoDesdeSwal'
    );
}
```

3. **Actualizar formulario:**
```blade
<form wire:submit.prevent="confirmAction">
```

## ✅ Ventajas de esta Arquitectura

- ✨ **Reutilizable:** Trait se puede usar en cualquier componente Livewire
- 🎨 **Consistente:** Todos los módulos usan el mismo estilo
- 🌙 **Modo oscuro:** Soporte automático
- 📱 **Responsive:** Alertas adaptadas a dispositivos móviles
- 🔧 **Mantenible:** Configuración centralizada en app.js
- ⚡ **Escalable:** Fácil agregar nuevos tipos de alertas

## 🚀 Siguientes Pasos

Aplicar la misma estructura a:
- CRUD de Pedidos
- CRUD de Mesas
- CRUD de Productos/Insumos
- CRUD de Proveedores
- CRUD de Clientes
