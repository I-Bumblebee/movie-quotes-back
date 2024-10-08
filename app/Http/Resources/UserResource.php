<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'          => $this->id,
			'name'        => $this->name,
			'image'       => $this->getFirstMediaUrl('profile_images') ?: Storage::url('images/default-avatar.jpg'),
			$this->mergeWhen(
				$request->routeIs('user', 'login', 'oauth.google.callback', 'user.update'),
				[
					'email' => $this->email,
					'oauth' => $this->google_id !== null,
				]
			),
		];
	}
}
