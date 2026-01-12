# Sistema E-Commerce - Don Pollón

## 📋 Resumen del Sistema

Sistema completo de e-commerce implementado para permitir que los clientes realicen pedidos online con validación de pago mediante voucher.

## 🎯 Flujo del Sistema

```
1. CLIENTE REGISTRA → Crea cuenta con email y contraseña
                     ↓
2. CLIENTE EXPLORA  → Ve catálogo público (sin auth necesario)
                     ↓
3. CLIENTE AGREGA   → Añade productos al carrito (requiere login)
                     ↓
4. CLIENTE CHECKOUT → Completa dirección + sube voucher
                     ↓
5. PEDIDO CREADO    → Estado 8: "Pago Pendiente"
                     ↓
6. CAJERO VALIDA    → Aprueba → Estado 9: "Pago Validado"
                     │          Rechaza → Estado 5: "Entregado a Comensales" + devuelve stock
                     ↓
7. COCINA PREPARA   → Estado 2: "En Preparación"
                     ↓
8. COCINA TERMINA   → Auto-asigna agente → Estado 10: "Pendiente de Envío"
                     ↓
9. AGENTE EN RUTA   → Estado 11: "Enviado"
                     ↓
10. AGENTE ENTREGA  → Estado 12: "Recibido"
```

## 🔐 Autenticación Multi-Guard

### Guard: `cliente`
- **Provider:** ClienteRegistrado
- **Campo de login:** emailCliente
- **Rutas:** `/catalogo`, `/registro`, `/login`, `/carrito`, `/checkout`, `/mis-pedidos`

### Guard: `web` (empleados)
- **Provider:** User (Empleado)
- **Rutas:** `/cajero/validar-pagos`, `/agente-pedidos/mis-pedidos`

## 📊 Nuevos Estados de Pedido

| ID | Nombre | Descripción |
|----|--------|-------------|
| 8 | Pago Pendiente | Pedido creado, esperando validación de pago |
| 9 | Pago Validado | Pago aprobado por cajero, listo para cocina |
| 10 | Pendiente de Envío | Cocina terminó, asignado a agente |
| 11 | Enviado | Agente en camino a entregar |
| 12 | Recibido | Cliente recibió el pedido |

## 👥 Nuevos Tipos de Empleado

| ID | Nombre | Función |
|----|--------|---------|
| 6 | Agente de Pedidos | Entrega pedidos online |

## 📦 Nuevos Tipos de Pedido

| ID | Nombre | Descripción |
|----|--------|-------------|
| 4 | Online | Pedido realizado desde catálogo web |

## 🗄️ Cambios en Base de Datos

### Tabla: `clientes_registrados`
**Nuevos campos:**
- `emailCliente` (string, unique) - Email para login
- `password` (string) - Contraseña hasheada
- `remember_token` (string, nullable)
- `email_verified_at` (timestamp, nullable)
- `created_at`, `updated_at` (timestamps)

### Tabla: `pago_pedido`
**Nuevos campos:**
- `voucherUrl` (text, nullable) - URL de Cloudinary
- `voucherPublicId` (string, nullable) - ID público de Cloudinary
- `estadoValidacion` (enum: pendiente, aprobado, rechazado) - Estado del pago
- `motivoRechazo` (text, nullable) - Razón del rechazo

### Tabla: `pedido`
**Nuevos campos:**
- `idAgentePedidos` (int, nullable) - ID del agente asignado (FK a empleado)

## 🛠️ Componentes Livewire Implementados

### Para Clientes (Guard: `cliente`)

#### 1. `Catalogo/Index`
- **Ruta:** `/catalogo`
- **Función:** Catálogo público + gestión de carrito
- **Features:**
  - Búsqueda de productos
  - Filtro por categoría
  - Agregar/remover productos del carrito
  - Modal del carrito con incrementar/decrementar
  - Session-based cart

#### 2. `Catalogo/Registro`
- **Ruta:** `/registro`
- **Función:** Registro de nuevos clientes
- **Validaciones:**
  - DNI: 8 dígitos, único
  - Celular: 9 dígitos
  - Email: único
  - Password: mínimo 6 caracteres

#### 3. `Catalogo/Login`
- **Ruta:** `/login`
- **Función:** Autenticación de clientes
- **Features:**
  - Remember me
  - Redirección inteligente (carrito o catálogo)

#### 4. `Catalogo/Checkout`
- **Ruta:** `/checkout`
- **Función:** Finalizar compra
- **Features:**
  - Formulario de dirección de entrega
  - Upload de voucher a Cloudinary
  - Validación de stock
  - Creación de pedido completo (Pedido + DetalleCliente + DetallePedido + PagoPedido)
  - Decremento automático de stock

#### 5. `Catalogo/MisPedidos`
- **Ruta:** `/mis-pedidos`
- **Función:** Historial de pedidos del cliente
- **Features:**
  - Lista de todos sus pedidos
  - Estado del pago (pendiente/aprobado/rechazado)
  - Tracking visual de entrega (4 pasos)
  - Ver voucher adjunto

### Para Empleados (Guard: `web`)

#### 6. `Cajero/ValidarPagos`
- **Ruta:** `/cajero/validar-pagos`
- **Función:** Validar pagos de pedidos online
- **Features:**
  - Lista de pedidos con estado 8 (Pago Pendiente)
  - Visualización de vouchers
  - Aprobar pago → Estado 9
  - Rechazar pago → Estado 5 + devolver stock
  - Modal para motivo de rechazo

#### 7. `AgentePedidos/MisPedidos`
- **Ruta:** `/agente-pedidos/mis-pedidos`
- **Función:** Gestión de entregas
- **Features:**
  - Lista de pedidos asignados al agente
  - Filtros por estado (10, 11, 12)
  - Marcar "En Tránsito" → Estado 11
  - Marcar "Entregado" → Estado 12
  - Ver dirección con referencias

### Modificado

#### 8. `Cocina/Index`
- **Cambios:**
  - Ahora carga pedidos con estados 2 y 9
  - Al terminar un pedido tipo 4 (Online):
    - Busca primer agente disponible (tipo 6, estado 1)
    - Asigna automáticamente
    - Cambia a estado 10 (Pendiente de Envío)

## 📱 Vistas Blade Creadas

| Vista | Layout | Descripción |
|-------|--------|-------------|
| `livewire/catalogo/index.blade.php` | catalogo | Grid de productos + modal carrito |
| `livewire/catalogo/registro.blade.php` | catalogo | Formulario de registro |
| `livewire/catalogo/login.blade.php` | catalogo | Formulario de login |
| `livewire/catalogo/checkout.blade.php` | catalogo | Checkout + upload voucher |
| `livewire/catalogo/mis-pedidos.blade.php` | catalogo | Historial con tracking |
| `livewire/cajero/validar-pagos.blade.php` | dashboard | Validación de pagos |
| `livewire/agente-pedidos/mis-pedidos.blade.php` | dashboard | Gestión de entregas |

## 🎨 Layout: `catalogo.blade.php`

**Features:**
- Navbar con logo
- Icono de carrito con contador de items
- Auth condicional:
  - Logueado: Muestra nombre + enlace "Mis Pedidos" + botón Salir
  - No logueado: Botones Login/Registro
- Footer simple
- Dark mode support

## 🔄 Flujo de Stock

### Al crear pedido (Checkout):
```php
foreach ($carrito as $item) {
    $producto->stockProducto -= $item['cantidad'];
    $producto->save();
}
```

### Al rechazar pago (Cajero):
```php
foreach ($pedido->detallePedidos as $detalle) {
    $producto->stockProducto += $detalle->cantidadDetallePedido;
    $producto->save();
}
```

## 🔑 Permisos Necesarios (Spatie)

**Pendiente de crear:**
- `validar-pagos` → Asignar a rol "Cajero"
- `ver-agente-pedidos` → Asignar a rol "Agente de Pedidos"

## 📋 Menú Sidebar Actualizado

**Grupo: Operaciones**
- ✅ Vista de Cocina
- ✅ Vista de Mozo
- **🆕 Validar Pagos** (Cajero)
- **🆕 Mis Entregas** (Agente de Pedidos)
- ✅ Mesas
- ✅ Clientes

## 🧪 Testing

### Para probar el flujo completo:

1. **Registrar cliente:**
   - Ir a `/registro`
   - Completar formulario
   - Login automático

2. **Hacer pedido:**
   - Agregar productos al carrito
   - Ir a `/checkout`
   - Completar dirección
   - Subir imagen de voucher
   - Confirmar

3. **Validar pago (Cajero):**
   - Login como empleado tipo 4 (Cajero)
   - Ir a `/cajero/validar-pagos`
   - Ver voucher
   - Aprobar o rechazar

4. **Preparar (Cocina):**
   - Login como empleado tipo 3 (Cocinero)
   - Ir a `/cocina`
   - Ver pedido estado 9
   - Marcar "En Preparación" → Estado 2
   - Terminar → Auto-asigna agente → Estado 10

5. **Entregar (Agente):**
   - Login como empleado tipo 6 (Agente de Pedidos)
   - Ir a `/agente-pedidos/mis-pedidos`
   - Ver pedido asignado
   - "En Tránsito" → Estado 11
   - "Entregado" → Estado 12

6. **Verificar (Cliente):**
   - Login como cliente
   - Ir a `/mis-pedidos`
   - Ver tracking completo

## 🚀 Rutas Públicas

| Ruta | Método | Auth | Descripción |
|------|--------|------|-------------|
| `/catalogo` | GET | No | Catálogo público |
| `/registro` | GET | guest:cliente | Registro de cliente |
| `/login` | GET | guest:cliente | Login de cliente |

## 🔒 Rutas Protegidas (Cliente)

| Ruta | Método | Auth | Descripción |
|------|--------|------|-------------|
| `/carrito` | GET | auth:cliente | Ver carrito |
| `/checkout` | GET | auth:cliente | Finalizar compra |
| `/mis-pedidos` | GET | auth:cliente | Historial |
| `/logout` | POST | auth:cliente | Cerrar sesión |

## 🔒 Rutas Protegidas (Empleados)

| Ruta | Método | Auth | Descripción |
|------|--------|------|-------------|
| `/cajero/validar-pagos` | GET | auth:web | Validar pagos |
| `/agente-pedidos/mis-pedidos` | GET | auth:web | Gestionar entregas |

## 📦 Dependencias Utilizadas

- **Livewire 3.6:** Framework reactivo
- **Cloudinary PHP SDK:** Upload de vouchers
- **Spatie Laravel Permission:** Control de acceso
- **Tailwind CSS:** Estilos
- **SweetAlert2 (via trait):** Alertas

## ⚙️ Configuración de Cloudinary

**Archivo:** `config/cloudinary.php`

Las credenciales están en `.env`:
```env
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
```

## 🎯 Próximos Pasos Sugeridos

1. **Crear permisos Spatie:**
   ```php
   Permission::create(['name' => 'validar-pagos']);
   Permission::create(['name' => 'ver-agente-pedidos']);
   
   $cajero = Role::findByName('Cajero');
   $cajero->givePermissionTo('validar-pagos');
   
   $agente = Role::findByName('Agente de Pedidos'); // Crear este rol
   $agente->givePermissionTo('ver-agente-pedidos');
   ```

2. **Agregar middleware de permisos:**
   ```php
   // routes/cajero.php
   Route::middleware(['auth:web', 'can:validar-pagos'])
   
   // routes/agente-pedidos.php
   Route::middleware(['auth:web', 'can:ver-agente-pedidos'])
   ```

3. **Notificaciones:**
   - Email al cliente cuando pago es aprobado/rechazado
   - Notificación al agente cuando se le asigna pedido

4. **Mejoras UX:**
   - Loading states con wire:loading
   - Confirmaciones con SweetAlert
   - Validación en tiempo real
   - Toast notifications

5. **Reportes:**
   - Dashboard de pedidos online
   - Estadísticas de ventas por canal
   - Performance de agentes

## 📝 Notas Importantes

- **Carrito:** Almacenado en sesión, no en base de datos
- **Stock:** Se decrementa al crear pedido, se devuelve al rechazar pago
- **Auto-asignación:** Cocina asigna automáticamente al primer agente disponible
- **Vouchers:** Subidos a Cloudinary, se guardan URL y public_id
- **Validación:** Solo cajero puede aprobar/rechazar pagos
- **Tracking:** Cliente ve 4 etapas visuales (Validado → Listo → En Tránsito → Entregado)

## 🐛 Troubleshooting

### Error: "Unauthenticated"
- Verificar que el guard correcto esté en uso
- Clientes: `auth('cliente')`
- Empleados: `auth('web')` o `auth()`

### Error: "Route not found"
- Ejecutar `php artisan route:clear`
- Verificar `routes/web.php` incluye `catalogo.php`, `cajero.php`, `agente-pedidos.php`

### Cloudinary no funciona
- Verificar credenciales en `.env`
- Verificar trait `WithCloudinaryUpload` está importado
- Verificar método `uploadToCloudinary()` se está llamando

### Stock negativo
- Verificar validación en `Checkout::finalizarCompra()`
- Verificar devolución de stock en `ValidarPagos::rechazarPago()`

---

**Fecha de implementación:** 12 de Enero, 2026  
**Desarrollado por:** GitHub Copilot  
**Sistema:** Don Pollón - Gestión de Pollería
