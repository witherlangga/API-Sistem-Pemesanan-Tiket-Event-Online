@props(['href' => '#', 'variant' => 'solid'])
@php
    switch($variant) {
        case 'outline':
            $base = 'inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 rounded font-medium border transition';
            break;
        case 'link':
            $base = 'inline-flex items-center gap-2 text-indigo-600 hover:underline';
            break;
        default:
            $base = 'inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 transition';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $base]) }}>
    {{ $slot ?? 'Lihat' }}
</a>