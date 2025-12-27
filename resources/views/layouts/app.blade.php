<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="/resources/css/app.css">
    @endif
</head>
<body class="min-h-screen bg-[#FDFDFC] p-6">
    <header class="max-w-4xl mx-auto mb-6 flex justify-end">
        @auth
            <div class="flex items-center gap-3">
                <span class="text-sm text-[#706f6c]">{{ auth()->user()->name }}</span>
                <a href="/dashboard" class="px-3 py-1 border rounded">Dashboard</a>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button class="px-3 py-1 border rounded">Logout</button>
                </form>
            </div>
        @endauth

        @guest
            <a href="/login" class="px-3 py-1 border rounded">Login</a>
        @endguest
    </header>

    <main class="max-w-4xl mx-auto">
        @yield('content')
    </main>
</body>
</html>
