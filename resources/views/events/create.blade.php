<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Event</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Tambah Event</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/events" method="POST" class="space-y-4">
        @csrf

        <input type="text" name="title" placeholder="Judul Event"
               class="w-full border p-2 rounded" required>

        <input type="text" name="category" placeholder="Kategori"
               class="w-full border p-2 rounded" required>

        <input type="text" name="location" placeholder="Lokasi"
               class="w-full border p-2 rounded" required>

        <input type="date" name="event_date"
               class="w-full border p-2 rounded" required>

        <input type="time" name="event_time"
               class="w-full border p-2 rounded" required>

        <input type="number" name="capacity" placeholder="Kapasitas"
               class="w-full border p-2 rounded" required>

        <div class="flex gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="/events" class="bg-gray-500 text-white px-4 py-2 rounded">
                Kembali
            </a>
        </div>
    </form>
</div>

</body>
</html>
