<?php

declare(strict_types=1);

namespace dwtie\PgnParser\Parser;

use dwtie\PgnParser\Struct\Move;
use dwtie\PgnParser\Struct\PGN;
use dwtie\PgnParser\Validator\ChessNotationValidator;

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

        self::parseMoveSequence($value, $pgn, 1, true);
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

        // Regular expression to check for a move number like "1." at the beginning of the string
        if (!preg_match('/^\d+\.(\.\.\.)?\s/', $value)) {
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
        $recombined = self::recombineVariationAndComments($explodedMoves);

        $moveTokens = array_values(array_filter($recombined, function ($token) {
            return !str_starts_with($token, '{')
                   && !str_starts_with($token, '(')
                   && !preg_match('/^\d+\.\.?\.?$/', $token);
        }));

        if (!isset($moveTokens[0]) || !ChessNotationValidator::isValid($moveTokens[0])) {
            return false;
        }

        if (isset($moveTokens[1]) && !ChessNotationValidator::isValid($moveTokens[1])) {
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
        $i = 0;

        while ($i < $moveCount) {
            $token = $notPreparedMoves[$i];

            if (empty($token)) {
                ++$i;
                continue;
            }

            if (preg_match('/^\d+\.\.?\.?$/', $token)) {
                $moves[] = $token;
            } elseif (str_starts_with($token, '{') || str_starts_with($token, '(')) {
                $startChar = $token[0];
                $endChar = self::$charStartEndMapping[$startChar];
                $level = 0;
                $parts = [];
                for (; $i < $moveCount; ++$i) {
                    $part = $notPreparedMoves[$i];
                    if (empty($part)) {
                        continue;
                    }

                    $parts[] = $part;
                    $level += substr_count($part, $startChar);
                    $level -= substr_count($part, $endChar);

                    if (0 === $level) {
                        break;
                    }
                }

                $recombined = implode(' ', $parts);
                $recombined = trim(substr($recombined, 1, -1));

                $moves[] = $startChar.$recombined.$endChar;
            } else {
                $moves[] = $token;
            }
            ++$i;
        }

        return $moves;
    }

    private static function parseMoveSequence(string $moveText, PGN $pgn, int $startMoveNumber, bool $isWhiteToMove): void
    {
        $moveNumber = $startMoveNumber;
        $isWhiteMove = $isWhiteToMove;
        $currentMove = null;

        $explodedMoves = explode(' ', $moveText);

        $moves = self::recombineVariationAndComments($explodedMoves);
        foreach ($moves as $moveStr) {
            if (preg_match('/^(\d+)\.$/', $moveStr, $matches)) {
                $moveNumber = (int) $matches[1];
                $isWhiteMove = true;

                continue;
            }

            if (preg_match('/^(\d+)\.\.\.$/', $moveStr, $matches)) {
                $moveNumber = (int) $matches[1];
                $isWhiteMove = false;

                continue;
            }

            if (str_starts_with($moveStr, '{')) {
                $comment = substr($moveStr, 1, -1);
                $currentMove?->setComment($comment);

                continue;
            }

            if (str_starts_with($moveStr, '(')) {
                $variationString = substr($moveStr, 1, -1);
                $variationPgn = new PGN();

                // A variation is an alternative to the next move in the main line.
                // So the state for the variation is the current state of the parser.
                self::parseMoveSequence($variationString, $variationPgn, $moveNumber, $isWhiteMove);

                if (count($variationPgn->getMoves()) > 0) {
                    $currentMove?->addVariation($variationPgn);
                }

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

            if (!$isWhiteMove) {
                ++$moveNumber;
            }

            $isWhiteMove = !$isWhiteMove;
        }
    }
}
