<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // GET /api/events
    public function index()
    {
        $events = Event::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar event berhasil diambil',
            'data' => $events
        ]);
    }

    // POST /api/events
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'address' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $event = Event::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Event berhasil dibuat',
            'data' => $event
        ], 201);
    }

    // GET /api/events/{event}
    public function show(Event $event)
    {
        return response()->json([
            'status' => true,
            'message' => 'Detail event berhasil diambil',
            'data' => $event
        ]);
    }

    // PUT /api/events/{event}
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'address' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,cancelled',
        ]);

        if ($event->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($validated['status'] === 'published' && $event->published_at === null) {
            $validated['published_at'] = now();
        }

        $event->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Event berhasil diperbarui',
            'data' => $event
        ]);
    }

    // DELETE /api/events/{event}
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'status' => true,
            'message' => 'Event berhasil dihapus'
        ]);
    }
}
