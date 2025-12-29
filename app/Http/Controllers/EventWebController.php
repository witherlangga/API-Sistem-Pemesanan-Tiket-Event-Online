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
        // Events list has been moved to the dashboard pages. Redirect to dashboard.
        return redirect('/dashboard');
    }

    /**
     * Menampilkan form buat event
     */
    public function create()
    {
        $user = request()->user();
        if (! $user || ! $user->isOrganizer()) {
            return redirect('/events')->with('error', 'Akses ditolak. Hanya organizer yang dapat membuat event.');
        }

        return view('events.create');
    }

    /**
     * Menyimpan event baru (default: draft)
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user || ! $user->isOrganizer()) {
            return redirect('/events')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'location'    => 'required|string|max:100',
            'event_date'  => 'required|date|after:today',
            'event_time'  => 'required',
            'capacity'    => 'required|integer|min:10',
        ]);

        Event::create([
            'user_id'      => $user->id,
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
        $user = request()->user();
        if (! $user || ! $user->isOrganizer() || $event->user_id !== $user->id) {
            return redirect('/events')->with('error', 'Akses ditolak.');
        }

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
        $user = request()->user();
        if (! $user || ! $user->isOrganizer() || $event->user_id !== $user->id) {
            return redirect('/events')->with('error', 'Akses ditolak.');
        }

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
        $user = request()->user();
        if (! $user || ! $user->isOrganizer() || $event->user_id !== $user->id) {
            return redirect('/events')->with('error', 'Akses ditolak.');
        }

        $event->delete();

        return redirect('/events')
            ->with('success', 'Event berhasil dihapus');
    }

    /**
     * Publish event
     */
    public function publish(Event $event)
    {
        $user = request()->user();
        if (! $user || ! $user->isOrganizer() || $event->user_id !== $user->id) {
            return redirect('/events')->with('error', 'Akses ditolak.');
        }

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
        $user = request()->user();
        if (! $user || ! $user->isOrganizer() || $event->user_id !== $user->id) {
            return redirect('/events')->with('error', 'Akses ditolak.');
        }

        $event->update([
            'status' => 'cancelled',
        ]);

        return redirect('/events')
            ->with('success', 'Event berhasil dibatalkan');
    }

    /**
     * Show public event details.
     */
    public function show(Event $event)
    {
        $user = request()->user();

        // Allow viewing if published OR if owner (organizer).
        if ($event->status !== 'published') {
            if (! $user || $user->id !== $event->user_id) {
                abort(404);
            }
        }

        // eager load organizer
        $event->load('user');

        return view('events.show', compact('event'));
    }
}
