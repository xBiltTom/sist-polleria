<?php

namespace Database\Seeders;

use App\Services\PermissionService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos para todos los módulos principales
        $modules = [
            'empleados',
            'productos',
            'insumos',
            'clientes',
            'proveedores',
            'mesas',
            'categorias',
            'usuarios',
            'roles',
            'tipos',
        ];

        foreach ($modules as $module) {
            PermissionService::createModulePermissions($module);
        }

        // Crear rol Super Admin con todos los permisos
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Crear roles comunes
        $adminRole = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        $adminRole->givePermissionTo([
            'ver-empleados', 'crear-empleados', 'editar-empleados',
            'ver-productos', 'crear-productos', 'editar-productos',
            'ver-insumos', 'crear-insumos', 'editar-insumos',
            'ver-clientes', 'crear-clientes', 'editar-clientes',
            'ver-proveedores', 'crear-proveedores', 'editar-proveedores',
            'ver-mesas', 'crear-mesas', 'editar-mesas',
            'ver-categorias', 'crear-categorias', 'editar-categorias',
            'ver-usuarios', 'crear-usuarios', 'editar-usuarios',
            'ver-tipos', 'crear-tipos', 'editar-tipos',
        ]);

        $cajeroRole = Role::firstOrCreate(['name' => 'cajero', 'guard_name' => 'web']);
        $cajeroRole->givePermissionTo([
            'ver-productos',
            'ver-clientes', 'crear-clientes',
            'ver-mesas',
        ]);

        $mozoRole = Role::firstOrCreate(['name' => 'mozo', 'guard_name' => 'web']);
        $mozoRole->givePermissionTo([
            'ver-productos',
            'ver-mesas',
            'ver-clientes',
        ]);

        $cocineroRole = Role::firstOrCreate(['name' => 'cocinero', 'guard_name' => 'web']);
        $cocineroRole->givePermissionTo([
            'ver-productos',
        ]);

        $this->command->info('Roles y permisos creados correctamente.');
        $this->command->info('Roles creados: super-admin, administrador, cajero, mozo, cocinero');
        $this->command->info('Total de permisos: ' . Permission::count());
    }
}
