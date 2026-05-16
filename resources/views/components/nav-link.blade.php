@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-0.5 py-0 border-b-2 border-indigo-400 text-[11px] font-medium leading-none text-gray-900'
            : 'inline-flex items-center px-0.5 py-0 border-b-2 border-transparent text-[11px] font-medium leading-none text-gray-500 hover:text-gray-700 hover:border-gray-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>