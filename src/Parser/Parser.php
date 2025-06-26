<?php

declare(strict_types=1);

namespace dwtie\PgnParser\Parser;

use dwtie\PgnParser\Struct\PGN;

interface Parser
{
    public static function parse(mixed $value, PGN $pgn): void;

    public static function supports(mixed $value): bool;
}
