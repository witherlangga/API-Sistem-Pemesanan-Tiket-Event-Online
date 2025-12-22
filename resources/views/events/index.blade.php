<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Event</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Daftar Event</h1>

        @forelse ($events as $event)
            <div class="bg-white p-5 rounded shadow mb-4">
                <h2 class="text-xl font-semibold">
                    {{ $event->nama_event }}
                </h2>
                <p class="text-gray-600">{{ $event->deskripsi }}</p>

                <div class="text-sm text-gray-500 mt-2">
                    📅 {{ $event->tanggal_mulai }} s/d {{ $event->tanggal_selesai }} <br>
                    📍 {{ $event->lokasi }}
                </div>

                <span class="inline-block mt-2 px-3 py-1 text-xs rounded 
                    {{ $event->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                    {{ strtoupper($event->status) }}
                </span>
            </div>
        @empty
            <p class="text-gray-500">Belum ada event.</p>
        @endforelse
    </div>

</body>
</html>
