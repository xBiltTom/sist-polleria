<?php

namespace App\Console\Commands;

use App\Services\PermissionService;
use Illuminate\Console\Command;

class InitPermissions extends Command
{
    protected $signature = 'permissions:init';
    protected $description = 'Inicializa los módulos del sistema de permisos';

    public function handle()
    {
        $this->info('Inicializando módulos del sistema...');

        PermissionService::initializeSystemModules();

        $this->info('✓ Módulos del sistema inicializados correctamente');

        $routesWithoutPermission = PermissionService::getRoutesWithoutPermission();
        $count = count($routesWithoutPermission);

        if ($count > 0) {
            $this->warn("⚠ Hay {$count} rutas sin permiso asignado");
            $this->info("Usa 'php artisan route:list' para ver todas las rutas");
            $this->info("Visita /permisos en el navegador para gestionar los permisos");
        } else {
            $this->info('✓ Todas las rutas tienen permisos asignados');
        }

        return 0;
    }
}
