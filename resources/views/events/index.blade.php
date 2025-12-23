<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Event</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Manajemen Event</h1>
        <a href="/events/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Tambah Event
        </a>
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <table class="w-full border text-sm">
        <thead class="bg-gray-200">
        <tr>
            <th class="p-2 border">Judul</th>
            <th class="p-2 border">Lokasi</th>
            <th class="p-2 border">Tanggal</th>
            <th class="p-2 border">Status</th>
            <th class="p-2 border w-64">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($events as $event)
            <tr class="hover:bg-gray-50">
                <td class="p-2 border font-medium">{{ $event->title }}</td>
                <td class="p-2 border">{{ $event->location }}</td>
                <td class="p-2 border">{{ $event->event_date }}</td>
                <td class="p-2 border">
                    @if ($event->status === 'draft')
                        <span class="px-2 py-1 bg-gray-300 rounded text-xs">Draft</span>
                    @elseif ($event->status === 'published')
                        <span class="px-2 py-1 bg-green-500 text-white rounded text-xs">Published</span>
                    @else
                        <span class="px-2 py-1 bg-red-500 text-white rounded text-xs">Cancelled</span>
                    @endif
                </td>
                <td class="p-2 border flex flex-wrap gap-2">

                    {{-- Edit (hanya draft) --}}
                    @if ($event->status === 'draft')
                        <a href="/events/{{ $event->id }}/edit"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                            Edit
                        </a>
                    @endif

                    {{-- Publish --}}
                    @if ($event->status === 'draft')
                        <form action="/events/{{ $event->id }}/publish" method="POST">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                Publish
                            </button>
                        </form>
                    @endif

                    {{-- Cancel --}}
                    @if ($event->status === 'published')
                        <form action="/events/{{ $event->id }}/cancel" method="POST">
                            @csrf
                            <button class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs">
                                Cancel
                            </button>
                        </form>
                    @endif

                    {{-- Delete --}}
                    <form action="/events/{{ $event->id }}" method="POST"
                          onsubmit="return confirm('Yakin hapus event ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center p-4 text-gray-500">
                    Belum ada event
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
