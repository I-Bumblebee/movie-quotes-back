<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Http\Resources\QuoteResource;
use App\Models\Movie;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
	use AuthorizesRequests;

	public function index(Request $request): JsonResponse
	{
		$movies = QueryBuilder::for($request->user()->movies())
			->allowedFilters(['title'])
			->withCount('quotes')
			->paginate(25);

		return response()->json(new MovieCollection($movies));
	}

	/**
	 * @throws AuthorizationException
	 */
	public function show(Movie $movie): JsonResponse
	{
		$this->authorize('view', $movie);

		$movie->load([
			'genres',
			'quotes' => function ($query) {
				$query->withCount('likes', 'comments');
			},
		])->loadCount('quotes');

		return response()->json([
			'movie'  => new MovieResource($movie),
			'quotes' => QuoteResource::collection($movie->quotes),
		]);
	}

	/**
	 * @throws AuthorizationException
	 */
	public function edit(Movie $movie): JsonResponse
	{
		$this->authorize('update', $movie);

		return response()->json(new MovieResource($movie));
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = $request->user()->movies()->create($request->validated());

		$movie->genres()->attach($request->validated('genres'));

		$movie->addMediaFromRequest('poster')
			->toMediaCollection('posters');

		return response()->json(new MovieResource($movie), 201);
	}

	/**
	 * @throws FileDoesNotExist
	 * @throws FileIsTooBig
	 * @throws AuthorizationException
	 */
	public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
	{
		$this->authorize('update', $movie);
		$movie->update($request->validated());

		$movie->genres()->sync($request->validated('genres'));

		if ($request->hasFile('poster')) {
			$movie->clearMediaCollection('posters');

			$movie->addMediaFromRequest('poster')
				->toMediaCollection('posters');
		}

		return response()->json(new MovieResource($movie));
	}

	/**
	 * @throws AuthorizationException
	 */
	public function destroy(Movie $movie): JsonResponse
	{
		$this->authorize('delete', $movie);

		$movie->delete();

		return response()->json(null, 204);
	}
}
