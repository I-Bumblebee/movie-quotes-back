<?php

namespace App\Http\Requests;

use App\Rules\EnglishLettersOnly;
use App\Rules\GeorgianLettersOnly;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title.en'         => ['string', 'max:45', new EnglishLettersOnly],
			'title.ka'         => ['string', 'max:45', new GeorgianLettersOnly],
			'description.en'   => ['string', 'max:500', new EnglishLettersOnly],
			'description.ka'   => ['string', 'max:500', new GeorgianLettersOnly],
			'release_year'     => ['integer'],
			'director_name.en' => ['string', 'max:45', new EnglishLettersOnly],
			'director_name.ka' => ['string', 'max:45', new GeorgianLettersOnly],
			'poster'           => ['image', 'max:2048'],
			'genres'           => ['array', 'min:1'],
		];
	}
}
