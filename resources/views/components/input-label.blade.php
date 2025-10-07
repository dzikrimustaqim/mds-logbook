@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-sm text-mds-black dark:text-gray-200']) }}>
    {{ $value ?? $slot }}
</label>
