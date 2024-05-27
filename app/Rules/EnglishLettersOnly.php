<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnglishLettersOnly implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!preg_match('/^[a-zA-Z0-9\s\p{P}]*$/', $value)) {
			$fail('The :attribute field must only contain English letters, numbers, and special symbols.');
		}
	}
}
