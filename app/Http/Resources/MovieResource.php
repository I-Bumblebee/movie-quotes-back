<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MovieResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$data = [
			'id'          => $this->id,
			'title'       => $this->title,
			'description' => $this->description,
			'release_year'=> $this->release_year,
			'director'    => $this->director_name,
			'poster'      => $this->getFirstMediaUrl('posters') ?: Storage::url('images/default-poster.jpg'),
			'quotes_count'=> $this->quotes_count,
			'genres'      => GenreResource::collection($this->genres),
		];

		if ($request->header('With-Translations') == 'true') {
			$data = array_merge($data, [
				'title'       => $this->getTranslations('title'),
				'description' => $this->getTranslations('description'),
				'director'    => $this->getTranslations('director_name'),
			]);
		}

		return  $data;
	}
}
