{{--
    Componente de subida de imágenes con Cloudinary

    Props:
    - wire:model: La propiedad de Livewire para el archivo
    - :preview: URL de previsualización (temporal o existente)
    - :is-uploading: Estado de carga (true/false)
    - :label: Etiqueta del campo
    - :accept: Tipos de archivo aceptados (default: image/*)
    - :hint: Texto de ayuda
    - :error: Mensaje de error
    - :preview-type: 'avatar' o 'card' (default: card)
--}}

@props([
    'preview' => null,
    'isUploading' => false,
    'label' => 'Imagen',
    'accept' => 'image/*',
    'hint' => 'PNG, JPG o WEBP hasta 2MB',
    'error' => null,
    'previewType' => 'card',
    'existingImage' => null,
])

@php
    $imageUrl = $preview ?: $existingImage;
@endphp

<div
    x-data="{
        showPreviewModal: false,
        isUploading: false
    }"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
    </label>

    <div class="flex items-start gap-4">
        {{-- Área de previsualización --}}
        @if($previewType === 'avatar')
            <div class="relative flex-shrink-0">
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="Preview"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Indicador de carga sobre el avatar --}}
                <div
                    x-show="isUploading"
                    x-cloak
                    class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full"
                >
                    <svg class="w-8 h-8 animate-spin text-polleria-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        @else
            <div class="relative flex-shrink-0">
                <div class="w-32 h-24 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="Preview"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                            <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs">Sin imagen</span>
                        </div>
                    @endif
                </div>

                {{-- Indicador de carga sobre la card --}}
                <div
                    x-show="isUploading"
                    x-cloak
                    class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-lg"
                >
                    <svg class="w-8 h-8 animate-spin text-polleria-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        @endif

        {{-- Controles de subida --}}
        <div class="flex-1 space-y-2">
            <div class="flex items-center gap-2">
                {{-- Botón de subir archivo --}}
                <label class="relative cursor-pointer">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-polleria-500 hover:bg-polleria-600 text-white rounded-lg font-medium text-sm transition-colors dark:bg-polleria-600 dark:hover:bg-polleria-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span x-show="!isUploading">Subir imagen</span>
                        <span x-show="isUploading" x-cloak>Subiendo...</span>
                    </span>
                    <input
                        type="file"
                        {{ $attributes->wire('model') }}
                        accept="{{ $accept }}"
                        class="sr-only"
                        :disabled="isUploading"
                    >
                </label>

                {{-- Botón de previsualizar (ojito) --}}
                @if($imageUrl)
                    <button
                        type="button"
                        x-on:click="showPreviewModal = true"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg border-2 border-polleria-500 text-polleria-500 hover:bg-polleria-50 dark:border-polleria-400 dark:text-polleria-400 dark:hover:bg-polleria-900/30 transition-colors"
                        title="Ver imagen"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Texto de ayuda --}}
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ $hint }}
            </p>

            {{-- Barra de progreso --}}
            <div
                x-show="isUploading"
                x-cloak
                class="w-full"
            >
                <div class="h-1.5 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-polleria-500 rounded-full animate-pulse" style="width: 70%"></div>
                </div>
                <p class="text-xs text-polleria-600 dark:text-polleria-400 mt-1">
                    Procesando imagen...
                </p>
            </div>

            {{-- Error --}}
            @if($error)
                <p class="text-sm text-red-500 dark:text-red-400">{{ $error }}</p>
            @endif
        </div>
    </div>

    {{-- Modal de previsualización --}}
    <template x-teleport="body">
        <div
            x-show="showPreviewModal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70"
            x-on:click.self="showPreviewModal = false"
            x-on:keydown.escape.window="showPreviewModal = false"
        >
            <div
                x-show="showPreviewModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative max-w-3xl max-h-[90vh] bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-2xl"
            >
                {{-- Botón cerrar --}}
                <button
                    type="button"
                    x-on:click="showPreviewModal = false"
                    class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Imagen --}}
                <img
                    src="{{ $imageUrl }}"
                    alt="Preview"
                    class="max-w-full max-h-[90vh] object-contain"
                >
            </div>
        </div>
    </template>
</div>
