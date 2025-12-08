@props([
    'variant' => 'primary', // primary, secondary, danger, success
    'size' => 'md', // sm, md, lg
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary' => 'text-white bg-polleria-500 hover:bg-polleria-600 dark:bg-polleria-dark-600 dark:hover:bg-polleria-dark-500 focus:ring-polleria-500 dark:focus:ring-polleria-dark-500',
    'secondary' => 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-polleria-dark-800 dark:border-polleria-dark-600 dark:hover:bg-polleria-dark-700 focus:ring-gray-500',
    'danger' => 'text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500',
    'success' => 'text-white bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 focus:ring-green-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
