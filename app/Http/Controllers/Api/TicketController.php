<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\ApiResponse;

class TicketController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($request->has('event_id')) {
            $query->where('event_id', $request->get('event_id'));
        }

        $tickets = $query->where('is_active', true)->latest()->paginate(10);

        $data = \App\Http\Resources\TicketResource::collection($tickets)->response()->getData(true);
        return $this->success('Daftar tiket', $data);
    }

    public function show(Ticket $ticket)
    {
        $data = new \App\Http\Resources\TicketResource($ticket);
        return $this->success('Detail tiket', $data);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'organizer') {
            return $this->error('Unauthorized', null, 403);
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
            'is_active' => 'boolean',
        ]);

        $ticket = Ticket::create(array_merge($validated, ['sold' => 0]));

        try {
            \App\Models\ActivityLog::record($request->user()->id, 'ticket:create', $ticket, [], $request);
        } catch (\Exception $e) {
        }

        $data = new \App\Http\Resources\TicketResource($ticket);
        return $this->success('Tiket berhasil dibuat', $data, 201);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'organizer') {
            return $this->error('Unauthorized', null, 403);
        }

        // ensure the organizer owns the event related to this ticket
        if ($ticket->event->user_id !== $user->id) {
            return $this->error('Forbidden', null, 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'quota' => 'sometimes|required|integer|min:1',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
            'is_active' => 'boolean',
        ]);

        $ticket->update($validated);

        try {
            \App\Models\ActivityLog::record($request->user()->id, 'ticket:update', $ticket, $validated, $request);
        } catch (\Exception $e) {
        }

        $data = new \App\Http\Resources\TicketResource($ticket);
        return $this->success('Tiket berhasil diperbarui', $data);
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'organizer') {
            return $this->error('Unauthorized', null, 403);
        }

        // ensure the organizer owns the event related to this ticket
        if ($ticket->event->user_id !== $user->id) {
            return $this->error('Forbidden', null, 403);
        }

        $ticket->delete();

        try {
            \App\Models\ActivityLog::record($user->id, 'ticket:delete', $ticket, [], $request);
        } catch (\Exception $e) {
        }

        return $this->success('Tiket berhasil dihapus');
    }
}
