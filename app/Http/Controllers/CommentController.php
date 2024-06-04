<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function store(Quote $quote, StoreCommentRequest $request): JsonResponse
	{
		$comment = $quote->comments()->create([
			'comment' => $request->comment,
			'user_id' => $request->user()->id,
		]);

		NotifyUser::dispatch($comment, $quote->user);

		return response()->json(['message' => 'Comment added successfully']);
	}
}
