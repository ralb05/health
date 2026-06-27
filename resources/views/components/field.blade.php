@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'autocomplete' => null,
    'placeholder' => null,
    'hint' => null,
])

<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-navy-700">
        {{ $label }}
        @unless ($required)
            <span class="font-normal text-navy-300">(opcional)</span>
        @endunless
    </label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => 'w-full rounded-xl border bg-white px-4 py-3 text-navy-900 placeholder-navy-300 transition focus:outline-none focus:ring-2 ' . ($errors->has($name) ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-navy-200 focus:border-navy-400 focus:ring-navy-100')]) }}
    >

    @if ($hint && ! $errors->has($name))
        <p class="text-xs text-navy-300">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs font-medium text-red-500">{{ $message }}</p>
    @enderror
</div>
