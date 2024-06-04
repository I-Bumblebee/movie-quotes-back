<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Models\Quote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function store(Quote $quote, FormRequest $request): JsonResponse
	{
		$comment = $quote->comments()->create([
			'comment' => $request->input('comment'),
			'user_id' => $request->user()->id,
		]);

		NotifyUser::dispatch($comment, $quote->user);

		return response()->json(['message' => 'Comment added successfully']);
	}
}
