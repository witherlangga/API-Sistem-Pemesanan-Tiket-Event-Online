<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Event Hub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFDFC] p-6">
    @unless(request()->routeIs('login') || request()->routeIs('register'))
    <header class="bg-white sticky top-0 z-50 shadow-sm w-full">
        <div class="w-full px-0">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-6 pl-4">
                    <a href="/" class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded flex items-center justify-center font-bold">EH</div>
                        <span class="ml-2 font-semibold text-lg text-gray-800">{{ config('app.name', 'Event Hub') }}</span>
                    </a>

                    <nav class="hidden md:flex items-center gap-4 ml-6">
                        <a href="/" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded">Home</a>
                        <a href="/events" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded">Events</a>
                    </nav>
                </div>

                <div class="flex items-center gap-4 pr-4">
                    <form action="{{ url('/events') }}" method="GET" class="hidden sm:flex items-center border rounded overflow-hidden">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event" class="px-3 py-2 text-sm w-56 focus:outline-none" />
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm">Cari</button>
                    </form>

                    @guest
                        <a href="/login" class="text-sm text-gray-700 hover:text-gray-900">Login</a>
                        <a href="/register" class="text-sm text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded">Register</a>
                    @endguest

                    @auth
                        <div class="relative">
                            <button id="profile-menu-button" aria-expanded="false" class="flex items-center p-0 rounded-full focus:outline-none">
                                <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="h-9 w-9 rounded-full object-cover border">
                            </button>
                            <div id="profile-menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-800 ring-1 ring-black ring-opacity-5 py-1 z-20">
                                <a href="/profile" class="block px-4 py-2 text-sm hover:bg-gray-100">Profile</a>
                                <a href="/dashboard" class="block px-4 py-2 text-sm hover:bg-gray-100">Dashboard</a>
                                <form method="POST" action="/logout" class="px-4 py-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-sm hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile menu button -->
                    <div class="md:hidden ml-3 flex items-center">
                        <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:bg-gray-100" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <div class="pt-2 pb-3 space-y-1 px-2">
                <form action="{{ url('/events') }}" method="GET" class="flex items-center gap-2 px-3 py-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event" class="px-3 py-2 border rounded-l-md text-sm w-full focus:ring-2 focus:ring-indigo-500" />
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-r-md text-sm">Cari</button>
                </form>
                <a href="/" class="block px-3 py-2 rounded text-base text-gray-700 hover:bg-gray-100">Home</a>
                <a href="/events" class="block px-3 py-2 rounded text-base text-gray-700 hover:bg-gray-100">Events</a>
                @auth
                    <a href="/dashboard" class="block px-3 py-2 rounded text-base text-gray-700 hover:bg-gray-100">Dashboard</a>
                    @if(auth()->user()->isOrganizer())
                        <a href="/events/create" class="block px-3 py-2 rounded text-base text-gray-700 hover:bg-gray-100">Create Event</a>
                    @endif
                    <form method="POST" action="/logout" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="w-full text-left">Logout</button>
                    </form>
                @else
                    <a href="/login" class="block px-3 py-2 rounded text-base text-gray-700 hover:bg-gray-100">Login</a>
                    <a href="/register" class="block px-3 py-2 rounded text-base text-gray-700 hover:bg-gray-100">Register</a>
                @endauth
            </div>
        </div>
    </header>
    @endunless

    {{-- Back button (show when previous page exists and not on home) --}}
    @includeWhen(!request()->routeIs('home') && url()->previous() && url()->previous() !== url()->current(), 'components.back-button')

    <main class="max-w-full mx-auto px-6 lg:px-12">
        @yield('content')
    </main>

    <script>
        // Toggle mobile menu
        document.addEventListener('DOMContentLoaded', function(){
            var btn = document.getElementById('mobile-menu-button');
            var menu = document.getElementById('mobile-menu');
            if (btn) btn.addEventListener('click', function(){
                menu.classList.toggle('hidden');
            });

            // Profile menu toggle
            var pbtn = document.getElementById('profile-menu-button');
            var pmenu = document.getElementById('profile-menu');
            if (pbtn) pbtn.addEventListener('click', function(){
                pmenu.classList.toggle('hidden');
            });

            // Close menus when clicking outside
            document.addEventListener('click', function(e){
                if (pmenu && !pmenu.classList.contains('hidden')){
                    if (!pbtn.contains(e.target) && !pmenu.contains(e.target)){
                        pmenu.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>
