@props(['value'])

<label {{ $attributes->merge(['class' => 'text-right grid grid-cols-xlabel gap-4 font-medium text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
