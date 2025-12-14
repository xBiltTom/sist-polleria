<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Contactos de Proveedor
            </h2>
            <x-btn variant="secondary" href="{{ route('contacto-proveedor.create') }}" wire:navigate>
                + Nuevo contacto
            </x-btn>
        </div>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Gestión de contactos de proveedores del sistema
        </p>
    </x-slot>

    <x-card class="m-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Nombre, DNI o email..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>
    </x-card>

    <x-card class="m-4">
        <div>
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('nombreContactoProveedor')">
                            Nombre
                            @if($sortBy === 'nombreContactoProveedor')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3">DNI</th>
                        <th class="px-4 py-3">Celular</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($contactos as $contacto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $contacto->nombreContactoProveedor }} {{ $contacto->apellidoContactoProveedor }}
                                    </span>
                                    @if($contacto->fechaNacimientoContactoProveedor)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Nac: {{ $contacto->fechaNacimientoContactoProveedor->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $contacto->dniContactoProveedor }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $contacto->celularContactoProveedor ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                {{ $contacto->emailContactoProveedor ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <x-btn variant="secondary" size="sm" href="{{ route('contacto-proveedor.edit', $contacto) }}" wire:navigate>
                                        Editar
                                    </x-btn>
                                    <x-btn variant="danger" size="sm" wire:click="confirmDelete({{ $contacto->idContactoProveedor }})">
                                        Desactivar
                                    </x-btn>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron contactos de proveedor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $contactos->links() }}
        </div>
    </x-card>
</div>
