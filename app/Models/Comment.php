<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Translatable\HasTranslations;

class Comment extends Model
{
	use HasFactory;

	protected $fillable = [
		'comment',
		'user_id',
		'quote_id',
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
