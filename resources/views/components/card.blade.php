@props([
    'padding' => 'p-5',
])

<div {{ $attributes->merge(['class' => "rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm $padding"]) }}>
    {{ $slot }}
</div>
