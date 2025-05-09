<?php

namespace HueHue\PgnParser\Validator;

interface ValidatorInterface
{
	/**
	 * Checks if the passed value is valid.
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function isValid(mixed $value): bool;
}
