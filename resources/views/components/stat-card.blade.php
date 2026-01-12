@props([
    'title',
    'value',
    'icon' => null,
    'trend' => null, // Puede ser 'up', 'down', o null
    'trendValue' => null,
    'variant' => 'default', // default, primary, success, warning, danger
])

@php
$iconBgColors = [
    'default' => 'bg-gray-100 dark:bg-gray-800',
    'primary' => 'bg-polleria-100 dark:bg-polleria-dark-700',
    'success' => 'bg-green-100 dark:bg-green-900/30',
    'warning' => 'bg-yellow-100 dark:bg-yellow-900/30',
    'danger' => 'bg-red-100 dark:bg-red-900/30',
];

$iconTextColors = [
    'default' => 'text-gray-600 dark:text-gray-400',
    'primary' => 'text-polleria-600 dark:text-polleria-dark-400',
    'success' => 'text-green-600 dark:text-green-400',
    'warning' => 'text-yellow-600 dark:text-yellow-400',
    'danger' => 'text-red-600 dark:text-red-400',
];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-polleria-dark-800 rounded-xl p-6 border border-gray-200 dark:border-polleria-dark-700']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $value }}</p>
        </div>
        @if($icon)
            <div class="p-3 rounded-lg {{ $iconBgColors[$variant] }}">
                <x-sidebar-icon :icon="$icon" class="w-6 h-6 {{ $iconTextColors[$variant] }}" />
            </div>
        @endif
    </div>
    @if($trend && $trendValue)
        <p class="text-sm mt-4 {{ $trend === 'up' ? 'text-green-500' : 'text-red-500' }}">
            @if($trend === 'up')
                <span>↑</span>
            @else
                <span>↓</span>
            @endif
            {{ $trendValue }}
        </p>
    @elseif(isset($footer))
        <div class="mt-4">
            {{ $footer }}
        </div>
    @endif
</div>
