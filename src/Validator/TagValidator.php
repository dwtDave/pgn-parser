<?php

namespace HueHue\PgnParser\Validator;

final class TagValidator implements ValidatorInterface
{
	public function isValid(mixed $value): bool
	{
		$value = trim($value);

		if (preg_match('/\[(\w+)\s"(.*?)"]/', $value)) {
			return true;
		}

		return false;
	}
}
