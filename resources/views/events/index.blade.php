@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Full-bleed hero -->
    <section class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white py-20">
        <div class="w-full px-6 lg:px-12">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">Events</h1>
                <p class="mt-3 text-lg text-indigo-100">Temukan acara menarik di dekat Anda — konser, workshop, meetup, dan banyak lagi.</p>


            </div>
        </div>
    </section>

    <!-- Events list (full width) -->
    <section class="w-full py-12 px-6 lg:px-12">
        @if($events->isEmpty())
            <div class="max-w-4xl mx-auto bg-white p-8 rounded shadow text-center">
                <h3 class="text-lg font-semibold">Belum ada event</h3>
                <p class="text-sm text-gray-500">Coba ubah kata kunci pencarian atau kembali nanti.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($events as $event)
                    <article class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                        <div class="relative pb-[56.25%] bg-gray-100 overflow-hidden">
                            <img src="{{ asset($event->poster ?? 'images/hero-events.svg') }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex-1">
                                <h3 class="text-lg md:text-xl font-semibold leading-tight">{{ $event->title }}</h3>
                                <p class="text-xs text-gray-500 mt-2">{{ $event->event_date }} • {{ $event->location }}</p>
                                <p class="text-sm text-gray-700 mt-3 line-clamp-3">{{ \Illuminate\Support\Str::limit($event->description, 140) }}</p>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <a href="{{ url('/events/' . $event->id) }}" class="text-sm px-2 py-1 bg-indigo-600 text-white rounded">Lihat</a>
                                <div class="text-xs">
                                    @if($event->status === 'published')
                                        <span class="px-2 py-0.5 bg-green-500 text-white rounded">Published</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-200 text-gray-700 rounded">{{ ucfirst($event->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10 flex justify-center">
                {{ $events->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
