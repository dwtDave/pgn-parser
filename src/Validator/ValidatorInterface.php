<?php

declare(strict_types=1);

namespace HueHue\PgnParser\Validator;

interface ValidatorInterface
{
    /**
     * Checks if the passed value is valid.
     */
    public static function isValid(mixed $value): bool;
}
