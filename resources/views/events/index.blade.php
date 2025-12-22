<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Event</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Daftar Event</h1>
            <a href="/events/create" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Event
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Judul</th>
                    <th class="p-2 border">Lokasi</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td class="p-2 border">{{ $event->title }}</td>
                        <td class="p-2 border">{{ $event->location }}</td>
                        <td class="p-2 border">{{ $event->event_date }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="/events/{{ $event->id }}/edit"
                               class="bg-yellow-500 text-white px-3 py-1 rounded">
                                Edit
                            </a>

                            <form action="/events/{{ $event->id }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus event ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-3 py-1 rounded">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">
                            Belum ada event
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
