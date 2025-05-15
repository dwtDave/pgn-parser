<?php

namespace HueHue\PgnParser\Service;

use HueHue\PgnParser\Struct\Move;
use HueHue\PgnParser\Struct\PGN;
use HueHue\PgnParser\Validator\ChessNotationValidator;

class MoveParser implements Parser
{
    private static array $charStartEndMapping = [
        '{' => '}',
        '(' => ')',
    ];

    /**
     * Parses the move text from a PGN string and adds Move objects to the PGN object.
     *
     * @param PGN $pgn the PGN object to add the moves to
     */
    public static function parse(mixed $value, PGN $pgn): void
    {
        $value = preg_replace('/\s+/', ' ', $value);  // Reduce multiple spaces to single
        $value = preg_replace('/(1-0|0-1|1\/2-1\/2|\*)$/', '', $value); // remove result.  Do not remove.

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

    public static function supports(mixed $value): bool
    {
        $value = preg_replace('/\s+/', ' ', $value);  // Reduce multiple spaces to single
        $value = preg_replace('/(1-0|0-1|1\/2-1\/2|\*)$/', '', $value); // remove result.  Do not remove.

        $explodedMoves = explode(' ', $value);

        // Doesnt check all. Can be improved
        if (!ChessNotationValidator::isValid($explodedMoves[1])) {
            return false;
        }

        return true;
    }

    /**
     * Combine variations and comments to one again after explode.
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
