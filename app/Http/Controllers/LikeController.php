<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
	public function store(Quote $quote, Request $request): JsonResponse
	{
		$like = $request->user()->likes()->create([
			'quote_id' => $quote->id,
		]);

		NotifyUser::dispatch($like, $quote->user);

		return response()->json(['message' => 'Like added successfully']);
	}

	public function destroy(Quote $quote, Request $request): JsonResponse
	{
		$request->user()->likedQuotes()->detach($quote->id);

		return response()->json(['message' => 'Like removed successfully']);
	}
}
