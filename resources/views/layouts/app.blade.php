<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FDFDFC] p-6">
    @unless(request()->routeIs('login') || request()->routeIs('register'))
    <header class="bg-white/95 backdrop-blur-sm shadow mb-6 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-3">
                        <div class="h-8 w-8 bg-blue-600 rounded flex items-center justify-center text-white font-bold">A</div>
                        <span class="ml-2 font-semibold text-lg text-gray-800">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <nav class="hidden md:flex md:ml-8 md:space-x-4 items-baseline">
                        <a href="/" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded">Home</a>
                        <a href="/events" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded">Events</a>
                        @auth
                            <a href="/dashboard" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded">Dashboard</a>
                            @if(auth()->user()->isCustomer())
                                <a href="/transactions" class="text-gray-600 hover:text-gray-800 px-3 py-2 rounded">Transaksi Saya</a>
                            @endif
                            @if(auth()->user()->isOrganizer())
                                <a href="/events/create" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded">Create Event</a>
                            @endif
                        @endauth
                    </nav>
                </div>

                <div class="flex items-center">
                    @guest
                        <a href="/login" class="text-sm text-gray-700 hover:text-gray-900 mr-4">Login</a>
                        <a href="/register" class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded">Register</a>
                    @endguest

                    @auth
                        <div class="relative">
                            <button id="profile-menu-button" aria-expanded="false" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100">
                                <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="profile-menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-20">
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <form method="POST" action="/logout" class="px-4 py-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden ml-3 flex items-center">
                    <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-100" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="pt-2 pb-3 space-y-1 px-2">
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

    <main class="max-w-4xl mx-auto">
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
