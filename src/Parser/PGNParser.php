<?php

namespace HueHue\PgnParser\Parser;

use HueHue\PgnParser\Struct\PGN;

/**
 * PHP PGN Parser.
 *
 * A class-based parser for Portable Game Notation (PGN) files.  This parser
 * focuses on extracting moves and tags from PGN strings.
 */
class PGNParser
{
    /**
     * Parser and order to iterate through.
     *
     * @var array<int, class-string<Parser>>
     */
    protected static array $parser = [
        TagParser::class,
        MoveParser::class,
    ];

    /**
     * Parses a PGN string and returns a PGN object.
     *
     * @param string $pgnString the PGN string to parse
     *
     * @return PGN|null a PGN object representing the parsed data, or null on error
     */
    public static function parse(string $pgnString): ?PGN
    {
        $pgn = new PGN();
        $lines = explode("\n", $pgnString);

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            foreach (self::$parser as $parser) {
                if ($parser::supports($line)) {
                    $parser::parse($line, $pgn);

                    break;
                }
            }
        }
        if (0 === count($pgn->getMoves()) && 0 === count($pgn->getTags())) {
            return null;
        }

        return $pgn;
    }
}
