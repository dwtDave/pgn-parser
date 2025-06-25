<?php

declare(strict_types=1);

namespace HueHue\PgnParser\Parser;

use HueHue\PgnParser\Struct\PGN;
use HueHue\PgnParser\Struct\Tag;
use HueHue\PgnParser\Validator\TagValidator;

class TagParser implements Parser
{
    /**
     * Parses a single PGN tag from a string and adds it to the PGN object.
     *
     * @param mixed $value the string to parse
     * @param PGN   $pgn   the PGN object to add the parsed tag to
     */
    public static function parse(mixed $value, PGN $pgn): void
    {
        // Create a Tag if the regex matches successfully.
        if (preg_match('/^\[([A-Za-z][A-Za-z0-9_-]*)\s"(.*?)"\]$/', $value, $matches)) {
            $tagName = $matches[1];
            $tagValue = $matches[2];

            $pgn->addTag(new Tag($tagName, $tagValue));
        }
    }

    public static function supports(mixed $value): bool
    {
        if (!TagValidator::isValid($value)) {
            return false;
        }

        return true;
    }
}
