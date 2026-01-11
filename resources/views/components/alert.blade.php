@props(['type' => 'info'])

@php
$colors = [
    'success' => 'bg-green-50 border-green-400 text-green-700',
    'error' => 'bg-red-50 border-red-400 text-red-700',
    'info' => 'bg-blue-50 border-blue-400 text-blue-700'
];
$class = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "mb-4 p-4 rounded-lg border-l-4 $class"]) }}>
  {{ $slot }}
</div>