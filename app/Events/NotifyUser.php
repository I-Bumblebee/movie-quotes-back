<?php

namespace App\Events;

use App\Http\Resources\NotificationResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyUser implements ShouldDispatchAfterCommit, ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public Notification $notification;

	public function __construct(Like|Comment $interaction, public User $recipient)
	{
		$this->notification = Notification::create([
			'user_id'         => $recipient->id,
			'notifiable_id'   => $interaction->id,
			'notifiable_type' => get_class($interaction),
		]);
	}

	public function broadcastOn(): Channel
	{
		return new Channel('User.' . $this->recipient->id . '.notifications');
	}

	public function broadcastWith(): array
	{
		return [
			'notification' => NotificationResource::make($this->notification),
		];
	}
}
