<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GeorgianLettersOnly implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!preg_match('/^[\p{Georgian}0-9\s\p{P}]*$/u', $value)) {
			$fail('The :attribute field must only contain Georgian letters, numbers, and special symbols.');
		}
	}
}
