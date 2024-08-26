<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
	public function toArray($request): array
	{
		return [
			'id'         => $this->id,
			'type'       => class_basename($this->notifiable_type),
			'initiator'  => UserResource::make($this->notifiable->user),
			'quote_id'   => $this->notifiable->quote_id,
			'is_read'    => $this->is_read,
			'created_at' => $this->created_at,
		];
	}
}
