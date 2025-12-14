<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Tipos de Comprobante</h2>
            <x-btn variant="secondary" href="{{ route('tipo-comprobante.create') }}" wire:navigate>+ Nuevo tipo</x-btn>
        </div>
    </x-slot>

    <x-card class="m-4">
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Descripción</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($tipos as $tipo)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $tipo->nombreTipoComprobante }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $tipo->descripcionTipoComprobante ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <x-btn variant="secondary" size="sm" href="{{ route('tipo-comprobante.edit', $tipo) }}" wire:navigate>Editar</x-btn>
                                <x-btn variant="danger" size="sm" wire:click="confirmDelete({{ $tipo->idTipoComprobante }})">Desactivar</x-btn>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">No se encontraron registros.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $tipos->links() }}</div>
    </x-card>
</div>
