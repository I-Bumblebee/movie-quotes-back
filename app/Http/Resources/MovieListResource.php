<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MovieListResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'           => $this->id,
			'title'        => $this->title,
			'release_year' => $this->release_year,
			'quotes_count' => $this->quotes_count,
			'poster'       => $this->getFirstMediaUrl('posters') ?: Storage::url('images/default-poster.jpg'),
		];
	}
}
