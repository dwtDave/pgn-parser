<?php

declare(strict_types=1);

namespace dwtie\PgnParser\Validator;

final class TagValidator implements ValidatorInterface
{
    public static function isValid(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $value = trim($value);

        if (preg_match('/^\[([A-Za-z][A-Za-z0-9_-]*)\s"(.*?)"\]$/', $value)) {
            return true;
        }

        return false;
    }
}
