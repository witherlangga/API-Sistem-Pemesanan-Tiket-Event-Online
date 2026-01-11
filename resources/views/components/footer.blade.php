<footer class="mt-12 bg-white/90 dark:bg-[#0a0a0a] py-6 border-t">
  <div class="max-w-full mx-auto px-6 lg:px-12 text-sm text-gray-600 dark:text-gray-400 flex flex-col md:flex-row items-center justify-between gap-4">
    <div>&copy; {{ date('Y') }} {{ config('app.name', 'Event Hub') }}. All rights reserved.</div>
    <div class="flex items-center gap-4">
      <a href="/" class="hover:underline">Home</a>
      <a href="/events" class="hover:underline">Events</a>
      <a href="/contact" class="hover:underline">Contact</a>
    </div>
  </div>
</footer>