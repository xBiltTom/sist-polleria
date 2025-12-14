<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Clientes Registrados
            </h2>
            <x-btn variant="secondary" href="{{ route('clientes.create') }}" wire:navigate>
                + Nuevo cliente
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestiona la base de datos de clientes
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre, DNI o RUC..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Cliente</label>
                <select wire:model.live="tipoCliente" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($tiposCliente as $tipo)
                        <option value="{{ $tipo->idTipoCliente }}">{{ $tipo->nombreTipoCliente }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombreCliente')">
                            Cliente
                            @if($sortBy === 'nombreCliente')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">Documento</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Contacto</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $cliente->nombreCliente }} {{ $cliente->apellidoCliente }}
                                    </p>
                                    @if($cliente->razonSocial)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cliente->razonSocial }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                @if($cliente->dniCliente)
                                    <div>DNI: {{ $cliente->dniCliente }}</div>
                                @endif
                                @if($cliente->RUC)
                                    <div>RUC: {{ $cliente->RUC }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $cliente->tipoCliente->nombreTipoCliente }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                <div>{{ $cliente->celularCliente ?? '-' }}</div>
                                @if($cliente->direccionCliente)
                                    <div class="text-xs text-gray-500">{{ Str::limit($cliente->direccionCliente, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('clientes.edit', $cliente) }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Editar
                                    </a>
                                    <button wire:click="confirmDelete({{ $cliente->idCliente }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron clientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $clientes->links() }}
        </div>
    </x-card>
</div>
