@props([
    'title',
    'description' => null,
])

<div class="flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $title }}
        </h2>
        @if($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $description }}
            </p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex space-x-3">
            {{ $actions }}
        </div>
    @endif
</div>
