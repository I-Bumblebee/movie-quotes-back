<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @return array<int|string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'data' => MovieListResource::collection($this->collection),
		];
	}

	/**
	 * Customize the pagination information for the resource.
	 *
	 * @param Request $request
	 * @param array   $paginated
	 * @param array   $default
	 *
	 * @return array
	 */
	public function paginationInformation(Request $request, array $paginated, array $default): array
	{
		return [
			'meta' => [
				'last_page' => $this->resource->lastPage(),
			],
		];
	}
}
