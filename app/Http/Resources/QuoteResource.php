<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class QuoteResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'             => $this->id,
			'image'          => $this->getFirstMediaUrl('quote_images') ?: Storage::url('images/default-quote-cover.jpg'),
			'likes_count'    => $this->whenNotNull($this->likes_count),
			'comments_count' => $this->whenNotNull($this->comments_count),
			'comments'       => CommentResource::collection($this->whenLoaded('comments')),
			'user'           => UserResource::make($this->user),
			$this->mergeWhen(
				$request->header('With-Translations') == 'true',
				['quote' => $this->getTranslations('quote')]
			),
			'quote'          => $this->quote,
			'movie'          => $this->whenLoaded(
				'movie',
				[
					'title'        => $this->movie->title,
					'release_year' => $this->movie->release_year,
				],
			),
			'liked' => $this->when(
				$request->routeIs('quotes.index'),
				$this->likes->contains('user_id', $request->user()->id)
			),
		];
	}
}
