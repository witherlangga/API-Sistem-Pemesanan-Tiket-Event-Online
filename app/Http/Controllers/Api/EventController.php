<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * GET /api/events
     * Menampilkan event yang sudah dipublish (untuk publik)
     */
    public function index()
    {
        $events = Event::published()
            ->latest()
            ->paginate(10);

        return response()->json([
            'status'  => true,
            'message' => 'Daftar event publik berhasil diambil',
            'data'    => $events
        ]);
    }

    /**
     * POST /api/events
     * Membuat event baru (default: draft)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:100',
            'location'    => 'required|string|max:100',
            'address'     => 'nullable|string',
            'event_date'  => 'required|date|after:today',
            'event_time'  => 'required',
            'capacity'    => 'required|integer|min:10',
        ]);

        $event = Event::create([
            'user_id'      => 1, // simulasi penyelenggara
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']),
            'description'  => $validated['description'] ?? null,
            'category'     => $validated['category'],
            'location'     => $validated['location'],
            'address'      => $validated['address'] ?? null,
            'event_date'   => $validated['event_date'],
            'event_time'   => $validated['event_time'],
            'capacity'     => $validated['capacity'],
            'status'       => 'draft',
            'published_at' => null,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Event berhasil dibuat sebagai draft',
            'data'    => $event
        ], 201);
    }

    /**
     * GET /api/events/{event}
     * Menampilkan detail event
     */
    public function show(Event $event)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Detail event berhasil diambil',
            'data'    => $event
        ]);
    }

    /**
     * PUT /api/events/{event}
     * Mengupdate event (hanya jika masih draft)
     */
    public function update(Request $request, Event $event)
    {
        if ($event->isPublished()) {
            return response()->json([
                'status'  => false,
                'message' => 'Event yang sudah dipublish tidak dapat diubah'
            ], 403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string|max:100',
            'location'    => 'required|string|max:100',
            'address'     => 'nullable|string',
            'event_date'  => 'required|date|after:today',
            'event_time'  => 'required',
            'capacity'    => 'required|integer|min:10',
        ]);

        if ($event->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $event->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Event berhasil diperbarui',
            'data'    => $event
        ]);
    }

    /**
     * DELETE /api/events/{event}
     * Menghapus event (soft delete)
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Event berhasil dihapus'
        ]);
    }

    /**
     * GET /api/my-events
     * Menampilkan event milik penyelenggara (semua status)
     */
    public function myEvents()
    {
        $events = Event::where('user_id', 1)
            ->latest()
            ->paginate(10);

        return response()->json([
            'status'  => true,
            'message' => 'Daftar event milik penyelenggara',
            'data'    => $events
        ]);
    }

    /**
     * POST /api/events/{event}/publish
     * Publish event
     */
    public function publish(Event $event)
    {
        if ($event->status !== 'draft') {
            return response()->json([
                'status'  => false,
                'message' => 'Event tidak dapat dipublish'
            ], 400);
        }

        $event->update([
            'status'       => 'published',
            'published_at' => now()
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Event berhasil dipublish'
        ]);
    }

    /**
     * POST /api/events/{event}/cancel
     * Membatalkan event
     */
    public function cancel(Event $event)
    {
        $event->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Event berhasil dibatalkan'
        ]);
    }
}
