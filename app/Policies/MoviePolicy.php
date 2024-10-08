<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MoviePolicy
{
	use HandlesAuthorization;

	public function view(User $user, Movie $movie): bool
	{
		return $user->id === $movie->user_id;
	}

	public function update(User $user, Movie $movie): bool
	{
		return $user->id === $movie->user_id;
	}

	public function delete(User $user, Movie $movie): bool
	{
		return $user->id === $movie->user_id;
	}
}
