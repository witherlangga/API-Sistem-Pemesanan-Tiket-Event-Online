<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventWebController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required',
            'location' => 'required',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'capacity' => 'required|integer|min:1'
        ]);

        $validated['user_id'] = 1;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = 'published';

        Event::create($validated);

        return redirect('/events')->with('success', 'Event berhasil ditambahkan');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required',
            'location' => 'required',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'capacity' => 'required|integer|min:1'
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $event->update($validated);

        return redirect('/events')->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect('/events')->with('success', 'Event berhasil dihapus');
    }
}
