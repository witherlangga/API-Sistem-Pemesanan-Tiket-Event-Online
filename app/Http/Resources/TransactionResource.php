<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'transaction_code' => $this->transaction_code,
            'user' => new UserResource($this->whenLoaded('user')),
            'event' => new EventResource($this->whenLoaded('event')),
            'ticket' => new TicketResource($this->whenLoaded('ticket')),
            'quantity' => $this->quantity,
            'price_per_ticket' => $this->price_per_ticket,
            'subtotal' => $this->subtotal,
            'admin_fee' => $this->admin_fee,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_proof' => $this->payment_proof,
            'paid_at' => $this->paid_at?->toDateTimeString(),
            'expired_at' => $this->expired_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
