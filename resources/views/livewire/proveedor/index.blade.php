<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Proveedores
            </h2>
            <x-btn variant="secondary" href="{{ route('proveedor.create') }}" wire:navigate>
                + Nuevo proveedor
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestión de proveedores del sistema
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Razón social o RUC..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select wire:model.live="estado" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    @foreach($estadosProveedor as $estado)
                        <option value="{{ $estado->idEstadoProveedor }}">{{ $estado->descripcionEstadoProveedor }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div>
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('razonSocialProveedor')">
                            Razón Social
                            @if($sortBy === 'razonSocialProveedor')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">RUC</th>
                        <th class="px-4 py-3">Contacto</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($proveedores as $proveedor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $proveedor->razonSocialProveedor }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $proveedor->rucProveedor }}
                            </td>
                            <td class="px-4 py-3">
                                @if($proveedor->contacto)
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 dark:text-white">
                                            {{ $proveedor->contacto->nombreContactoProveedor }} {{ $proveedor->contacto->apellidoContactoProveedor }}
                                        </span>
                                        @if($proveedor->contacto->celularContactoProveedor)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $proveedor->contacto->celularContactoProveedor }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-500">Sin contacto</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $proveedor->estadoProveedor->descripcionEstadoProveedor ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <x-btn variant="secondary" size="sm" href="{{ route('proveedor.edit', $proveedor) }}" wire:navigate>
                                        Editar
                                    </x-btn>
                                    <x-btn variant="danger" size="sm" wire:click="confirmDelete({{ $proveedor->idProveedor }})">
                                        Desactivar
                                    </x-btn>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron proveedores.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $proveedores->links() }}
        </div>
    </x-card>
</div>
