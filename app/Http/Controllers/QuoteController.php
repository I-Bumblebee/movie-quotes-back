<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\QueryBuilder\QueryBuilder;

class QuoteController extends Controller
{
	use AuthorizesRequests;

	public function index(Request $request): JsonResponse
	{
		$quotes = QueryBuilder::for(Quote::class)
			->allowedFilters(['movie.title', 'quote'])
			->with(['user', 'movie', 'user', 'comments', 'comments.user'])
			->withCount('comments', 'likes')
			->paginate(25);

		return QuoteResource::collection($quotes)->response();
	}

	public function show(Quote $quote): JsonResponse
	{
		$quote->load('comments', 'user', 'comments.user', 'likes')
			->loadCount('comments', 'likes');

		return QuoteResource::make($quote)->response();
	}

	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$quote = $request->user()->quotes()->create($request->validated());

		$quote->addMediaFromRequest('image')
			->toMediaCollection('quote_images');

		return QuoteResource::make($quote)->response()->setStatusCode(201);
	}

	/**
	 * @throws AuthorizationException
	 */
	public function destroy(Quote $quote): JsonResponse
	{
		$this->authorize('delete', $quote);
		$quote->delete();

		return response()->json(null, 204);
	}

	/**
	 * @throws FileDoesNotExist
	 * @throws FileIsTooBig
	 * @throws AuthorizationException
	 */
	public function update(UpdateQuoteRequest $request, Quote $quote): JsonResponse
	{
		$this->authorize('update', $quote);
		$quote->update($request->validated());

		if ($request->hasFile('image')) {
			$quote->clearMediaCollection('quote_images');

			$quote->addMediaFromRequest('image')
				->toMediaCollection('quote_images');
		}

		return QuoteResource::make($quote)->response();
	}
}
