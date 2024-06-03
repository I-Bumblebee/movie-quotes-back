<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function addComment(Quote $quote, Request $request): JsonResponse
	{
		$comment = $quote->comments()->create([
			'comment' => $request->input('comment'),
			'user_id' => $request->user()->id,
		]);

		NotifyUser::dispatch($comment, $quote->user);

		return response()->json(['message' => 'Comment added successfully']);
	}
}
