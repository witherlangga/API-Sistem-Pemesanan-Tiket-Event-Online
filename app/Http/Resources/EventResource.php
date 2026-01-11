<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category,
            'location' => $this->location,
            'address' => $this->address,
            'poster' => $this->poster,
            'event_date' => $this->event_date?->toDateString(),
            'event_time' => $this->event_time,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'published_at' => $this->published_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'organizer' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
