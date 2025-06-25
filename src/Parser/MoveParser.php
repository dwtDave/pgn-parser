<?php

namespace HueHue\PgnParser\Parser;

use HueHue\PgnParser\Struct\Move;
use HueHue\PgnParser\Struct\PGN;
use HueHue\PgnParser\Validator\ChessNotationValidator;

class MoveParser implements Parser
{
    /**
     * @var array<string, string>
     */
    private static array $charStartEndMapping = [
        '{' => '}',
        '(' => ')',
    ];

    /**
     * Parses the move text from a PGN string and adds Move objects to the PGN object.
     *
     * @param mixed $value The PGN move text to parse
     * @param PGN   $pgn   the PGN object to add the moves to
     */
    public static function parse(mixed $value, PGN $pgn): void
    {
        if (!is_string($value)) {
            return;
        }

        $value = preg_replace('/(1-0|0-1|1\/2-1\/2|\*)$/', '', $value);
        if (null === $value) {
            return;
        }
        $explodedMoves = explode(' ', $value);

        $moves = self::recombineVariationAndComments($explodedMoves);
        $moveNumber = 1;
        $isWhiteMove = true;
        $currentMove = null;
        foreach ($moves as $moveStr) {
            if (str_starts_with($moveStr, '{')) {
                $comment = substr($moveStr, 1, -1);

                $currentMove?->setComment($comment);

                continue;
            }

            if (str_starts_with($moveStr, '(')) {
                $variation = substr($moveStr, 1, -1);

                $currentMove?->addVariation($variation);

                continue;
            }

            if (!ChessNotationValidator::isValid($moveStr)) {
                continue;
            }

            preg_match('/(!!|!|!\?|\?\?|\?!|\?)?$/', $moveStr, $matches);
            $annotation = $matches[0] ?? null;
            if (null !== $annotation) {
                $moveStr = str_replace($annotation, '', $moveStr);
            }

            $move = new Move($moveStr, $moveNumber, $isWhiteMove, $annotation);
            $pgn->addMove($move);

            $currentMove = $move;

            if ($isWhiteMove) {
                ++$moveNumber;
            }

            $isWhiteMove = !$isWhiteMove;
        }
    }

    /**
     * Check if the value can be parsed by this parser.
     *
     * @param mixed $value The value to check
     *
     * @return bool True if the value can be parsed, false otherwise
     */
    public static function supports(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $value = preg_replace('/\s+/', ' ', $value);  // Reduce multiple spaces to single
        if (null === $value) {
            return false;
        }

        $value = preg_replace('/(1-0|0-1|1\/2-1\/2|\*)$/', '', $value);
        if (null === $value) {
            return false;
        }

        $explodedMoves = explode(' ', $value);

        // Doesnt check all. Can be improved
        if (!isset($explodedMoves[1]) || !ChessNotationValidator::isValid($explodedMoves[1])) {
            return false;
        }

        return true;
    }

    /**
     * Combine variations and comments to one again after explode.
     *
     * @param array<int, string> $notPreparedMoves
     *
     * @return array<int, string>
     */
    protected static function recombineVariationAndComments(array $notPreparedMoves): array
    {
        $moves = [];
        $moveCount = count($notPreparedMoves);
        $parts = [];
        $startChar = '';
        for ($i = 1; $i < $moveCount; ++$i) {
            if (empty($notPreparedMoves[$i])) {
                continue;
            }

            if (!str_starts_with($notPreparedMoves[$i], '{') && !str_starts_with($notPreparedMoves[$i], '(')) {
                $moves[] = $notPreparedMoves[$i];
                continue;
            }

            if ($notPreparedMoves[$i][0] !== $startChar) {
                $startChar = $notPreparedMoves[$i][0];
            }

            while (true) {
                $parts[] = $notPreparedMoves[$i];
                if (str_ends_with($notPreparedMoves[$i], self::$charStartEndMapping[$startChar])) {
                    $fullString = implode(' ', $parts);
                    $moves[] = $fullString;

                    $parts = [];

                    break;
                }
                ++$i;
            }
        }

        return $moves;
    }
}
