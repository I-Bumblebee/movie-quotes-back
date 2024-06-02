<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserBasicResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'name'  => $this->name,
			'image' => $this->getFirstMediaUrl('profile_images') ?: Storage::url('images/default-avatar.jpg'),
		];
	}
}
