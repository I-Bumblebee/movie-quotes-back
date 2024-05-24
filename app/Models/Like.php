<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Like extends Model
{
	use HasFactory;

	protected $fillable = [
		'quote_id',
		'user_id',
	];

	public function notification(): MorphOne
	{
		return $this->morphOne(Notification::class, 'notifiable');
	}

	public function quote(): BelongsTo
	{
		return $this->belongsTo(Quote::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
