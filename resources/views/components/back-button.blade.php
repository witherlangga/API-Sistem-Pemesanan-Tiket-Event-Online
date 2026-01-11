@php
    $previous = url()->previous();
    $current = url()->current();
@endphp

@if(!request()->routeIs('home') && $previous && $previous !== $current)
    <div class="px-6 lg:px-12 py-3">
        <a href="{{ $previous }}" onclick="event.preventDefault(); history.back();" class="inline-flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-100 px-3 py-2 rounded-md border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Kembali</span>
        </a>
    </div>
@endif