<?php

namespace App\Http\Requests;

use App\Rules\EnglishLettersOnly;
use App\Rules\GeorgianLettersOnly;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title.en'         => ['required', 'string', 'max:45', new EnglishLettersOnly],
			'title.ka'         => ['required', 'string', 'max:45', new GeorgianLettersOnly],
			'description.en'   => ['required', 'string', 'max:500', new EnglishLettersOnly],
			'description.ka'   => ['required', 'string', 'max:500', new GeorgianLettersOnly],
			'release_year'     => ['required', 'integer'],
			'director_name.en' => ['required', 'string', 'max:45', new EnglishLettersOnly],
			'director_name.ka' => ['required', 'string', 'max:45', new GeorgianLettersOnly],
			'poster'           => ['required', 'image', 'max:2048'],
			'genres'           => ['required', 'array', 'min:1'],
		];
	}
}
