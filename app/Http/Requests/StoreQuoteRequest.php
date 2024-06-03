<?php

namespace App\Http\Requests;

use App\Rules\EnglishLettersOnly;
use App\Rules\GeorgianLettersOnly;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'quote.en' => ['required', 'string', new EnglishLettersOnly],
			'quote.ka' => ['required', 'string', new GeorgianLettersOnly],
			'image'    => ['required', 'image', 'max:2048'],
			'movie_id' => ['required', 'exists:movies,id'],
		];
	}
}
