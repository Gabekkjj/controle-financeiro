@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-gray-900' // Estilo Ativo
            : 'flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white'; // Estilo Inativo
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>