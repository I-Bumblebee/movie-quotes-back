<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$notifications = auth()->user()->notifications()
			->with('notifiable', 'notifiable.user')
			->latest()
			->get();

		return NotificationResource::collection($notifications)->response();
	}

	public function markAsRead(Request $request): JsonResponse
	{
		auth()->user()->notifications()
			->whereIn('id', $request->input('notification_ids'))
			->update(['is_read' => true]);

		return response()->json(null, 204);
	}
}
