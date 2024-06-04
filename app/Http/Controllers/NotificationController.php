<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkNotificationsReadRequest;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;

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

	public function markAsRead(MarkNotificationsReadRequest $request): JsonResponse
	{
		auth()->user()->notifications()
			->whereIn('id', $request->input('notification_ids'))
			->update(['is_read' => true]);

		return response()->json(null, 204);
	}
}
