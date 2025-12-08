@props([
    'title' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-polleria-dark-800 rounded-xl shadow-sm border border-gray-200 dark:border-polleria-dark-700 overflow-hidden']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-gray-200 dark:border-polleria-dark-700">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-gray-50 dark:bg-polleria-dark-900 border-t border-gray-200 dark:border-polleria-dark-700">
            {{ $footer }}
        </div>
    @endif
</div>
