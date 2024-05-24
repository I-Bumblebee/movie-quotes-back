<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Movie extends Model implements HasMedia
{
	use HasFactory, InteractsWithMedia, HasTranslations;

	public array $translatable = ['title', 'description'];

	protected $fillable = [
		'title',
		'description',
		'release_year',
		'director_name',
		'user_id',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class);
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}
}
