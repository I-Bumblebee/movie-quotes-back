<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
	use HasFactory;

	protected $fillable = [
		'is_read',
		'user_id',
		'notifiable_type',
		'notifiable_id',
	];

	public static function booted(): void
	{
		static::addGlobalScope('unread', function ($builder) {
			$builder->where('is_read', false)
				->whereHas('notifiable');
		});
	}

	public function notifiable(): MorphTo
	{
		return $this->morphTo();
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
