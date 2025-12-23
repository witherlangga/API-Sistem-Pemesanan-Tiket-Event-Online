<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventWebController extends Controller
{
    /**
     * Menampilkan daftar event (semua status)
     */
    public function index()
    {
        $events = Event::latest()->get();
        return view('events.index', compact('events'));
    }

    /**
     * Menampilkan form buat event
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Menyimpan event baru (default: draft)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'location'    => 'required|string|max:100',
            'event_date'  => 'required|date|after:today',
            'event_time'  => 'required',
            'capacity'    => 'required|integer|min:10',
        ]);

        Event::create([
            'user_id'      => 1, // simulasi organizer
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']),
            'category'     => $validated['category'],
            'location'     => $validated['location'],
            'event_date'   => $validated['event_date'],
            'event_time'   => $validated['event_time'],
            'capacity'     => $validated['capacity'],
            'status'       => 'draft',
            'published_at' => null,
        ]);

        return redirect('/events')
            ->with('success', 'Event berhasil dibuat sebagai draft');
    }

    /**
     * Menampilkan form edit event
     */
    public function edit(Event $event)
    {
        if ($event->isPublished()) {
            return redirect('/events')
                ->with('error', 'Event yang sudah dipublish tidak dapat diedit');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Memperbarui event (hanya jika masih draft)
     */
    public function update(Request $request, Event $event)
    {
        if ($event->isPublished()) {
            return redirect('/events')
                ->with('error', 'Event yang sudah dipublish tidak dapat diperbarui');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'location'    => 'required|string|max:100',
            'event_date'  => 'required|date|after:today',
            'event_time'  => 'required',
            'capacity'    => 'required|integer|min:10',
        ]);

        $event->update([
            'title'      => $validated['title'],
            'slug'       => Str::slug($validated['title']),
            'category'   => $validated['category'],
            'location'   => $validated['location'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'capacity'   => $validated['capacity'],
        ]);

        return redirect('/events')
            ->with('success', 'Event berhasil diperbarui');
    }

    /**
     * Menghapus event (soft delete)
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect('/events')
            ->with('success', 'Event berhasil dihapus');
    }

    /**
     * Publish event
     */
    public function publish(Event $event)
    {
        if ($event->status !== 'draft') {
            return redirect('/events')
                ->with('error', 'Event tidak dapat dipublish');
        }

        $event->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        return redirect('/events')
            ->with('success', 'Event berhasil dipublish');
    }

    /**
     * Cancel event
     */
    public function cancel(Event $event)
    {
        $event->update([
            'status' => 'cancelled',
        ]);

        return redirect('/events')
            ->with('success', 'Event berhasil dibatalkan');
    }
}
