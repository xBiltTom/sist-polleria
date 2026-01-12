<div>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Dashboard
            @if($userRole === 'mozo')
                - Mozo
            @elseif($userRole === 'cajero')
                - Cajero
            @elseif($userRole === 'cocinero')
                - Cocina
            @elseif(in_array($userRole, ['administrador', 'super-admin']))
                - Administración
            @endif
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Bienvenido al panel de 
            @if($userRole === 'mozo')
                atención al cliente
            @elseif($userRole === 'cajero')
                caja y pagos
            @elseif($userRole === 'cocinero')
                gestión de cocina
            @else
                administración de la pollería
            @endif
        </p>
    </x-slot>

    <div class="p-6">
        {{-- DASHBOARD ADMINISTRADOR --}}
        @if(in_array($userRole, ['administrador', 'super-admin']))
            @include('livewire.dashboard.partials.admin-dashboard')
        @endif

        {{-- DASHBOARD CAJERO --}}
        @if($userRole === 'cajero')
            @include('livewire.dashboard.partials.cajero-dashboard')
        @endif

        {{-- DASHBOARD COCINERO --}}
        @if($userRole === 'cocinero')
            @include('livewire.dashboard.partials.cocinero-dashboard')
        @endif

        {{-- DASHBOARD MOZO --}}
        @if($userRole === 'mozo')
            @include('livewire.dashboard.partials.mozo-dashboard')
        @endif
    </div>
</div>
