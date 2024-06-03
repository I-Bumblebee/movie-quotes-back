<?php

namespace App\Http\Requests;

use App\Rules\EnglishLettersOnly;
use App\Rules\GeorgianLettersOnly;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'quote.en' => ['string', new EnglishLettersOnly],
			'quote.ka' => ['string', new GeorgianLettersOnly],
			'image'    => ['image', 'max:2048'],
		];
	}
}
