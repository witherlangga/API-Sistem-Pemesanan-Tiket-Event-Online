<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\ApiResponse;

class EventController extends Controller
{
    use ApiResponse;
    /**
     * GET /api/events
     * Menampilkan event yang sudah dipublish (untuk publik)
     */
    public function index(Request $request)
    {
        $query = Event::published();

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->filled('search')) {
            $q = $request->get('search');
            $query->where(function ($qbuilder) use ($q) {
                $qbuilder->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->get('date_to'));
        }

        $events = $query->latest()->paginate(10);

        // use resource collection for structured responses
        $data = \App\Http\Resources\EventResource::collection($events)->response()->getData(true);
        return $this->success('Daftar event publik berhasil diambil', $data);
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
            'user_id'      => $request->user()->id,
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

        try {
            \App\Models\ActivityLog::record(request()->user()->id ?? null, 'event:create', $event, [], request());
        } catch (\Exception $e) {
        }

        return $this->success('Event berhasil dibuat sebagai draft', $event, 201);
    }

    /**
     * GET /api/events/{event}
     * Menampilkan detail event
     */
    public function show(Event $event)
    {
        $data = new \App\Http\Resources\EventResource($event);
        return $this->success('Detail event berhasil diambil', $data);
    }

    /**
     * PUT /api/events/{event}
     * Mengupdate event (hanya jika masih draft)
     */
    public function update(Request $request, Event $event)
    {
        // ownership check
        if ($request->user()->id !== $event->user_id) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

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
        try {
            \App\Models\ActivityLog::record(request()->user()->id ?? null, 'event:update', $event, $validated, request());
        } catch (\Exception $e) {
        }
        return $this->success('Event berhasil diperbarui', $event);
    }

    /**
     * DELETE /api/events/{event}
     * Menghapus event (soft delete)
     */
    public function destroy(Event $event)
    {
        $user = request()->user();
        if ($user->id !== $event->user_id) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

        $event->delete();
        try {
            \App\Models\ActivityLog::record(request()->user()->id ?? null, 'event:delete', $event, [], request());
        } catch (\Exception $e) {
        }
        return $this->success('Event berhasil dihapus');
    }

    /**
     * GET /api/my-events
     * Menampilkan event milik penyelenggara (semua status)
     */
    public function myEvents(Request $request)
    {
        $events = Event::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
        return $this->success('Daftar event milik penyelenggara', $events);
    }

    /**
     * POST /api/events/{event}/publish
     * Publish event
     */
    public function publish(Event $event)
    {
        $user = request()->user();
        if ($user->id !== $event->user_id) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

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
        try {
            \App\Models\ActivityLog::record(request()->user()->id ?? null, 'event:publish', $event, [], request());
        } catch (\Exception $e) {
        }
        return $this->success('Event berhasil dipublish');
    }

    /**
     * POST /api/events/{event}/cancel
     * Membatalkan event
     */
    public function cancel(Event $event)
    {
        $user = request()->user();
        if ($user->id !== $event->user_id) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

        $event->update([
            'status' => 'cancelled'
        ]);
        try {
            \App\Models\ActivityLog::record(request()->user()->id ?? null, 'event:cancel', $event, [], request());
        } catch (\Exception $e) {
        }
        return $this->success('Event berhasil dibatalkan');
    }
}
