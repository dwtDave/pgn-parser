<?php

declare(strict_types=1);

namespace HueHue\PgnParser\Validator;

final class ChessNotationValidator implements ValidatorInterface
{
    public const string PIECE_MOVE_REGEX = '/^[KQRBN][a-h][1-8][+#]?[!?]{0,2}$/';
    public const string PIECE_CAPTURE_REGEX = '/^[KQRBN]x[a-h][1-8][+#]?[!?]{0,2}$/';
    public const string PAWN_MOVE_REGEX = '/^[a-h][1-8][+#]?[!?]{0,2}$/';
    public const string PAWN_CAPTURE_REGEX = '/^[a-h]x[a-h][1-8][+#]?[!?]{0,2}$/';
    public const string PAWN_PROMOTION_REGEX = '/^[a-h][1-8]=[QRBN][+#]?[!?]{0,2}$/';
    public const string PROMOTION_AFTER_CAPTURE_REGEX = '/^[a-h]x[a-h][1-8]=[QRBN][+#]?[!?]{0,2}$/';
    public const string DISAMBIGUATION_REGEX = '/^[KQRBN][a-h1-8]?[a-h][1-8][+#]?[!?]{0,2}$/';
    public const string DISAMBIGUATION_CAPTURE_REGEX = '/^[KQRBN][a-h][1-8]x[a-h][1-8][+#]?[!?]{0,2}$/';

    public static function isValid(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $value = trim($value);

        if (preg_match(self::PIECE_MOVE_REGEX, $value)) {
            return true;
        }

        if (preg_match(self::PIECE_CAPTURE_REGEX, $value)) {
            return true;
        }

        if (preg_match(self::PAWN_MOVE_REGEX, $value)) {
            return true;
        }

        if (preg_match(self::PAWN_CAPTURE_REGEX, $value)) {
            return true;
        }

        if (preg_match(self::PAWN_PROMOTION_REGEX, $value)) {
            return true;
        }

        if (preg_match(self::PROMOTION_AFTER_CAPTURE_REGEX, $value)) {
            return true;
        }

        if (in_array($value, ['O-O', 'O-O-O'])) {
            return true;
        }

        if (preg_match(self::DISAMBIGUATION_REGEX, $value)) {
            return true;
        }

        // Disambiguation with capture
        if (preg_match(self::DISAMBIGUATION_CAPTURE_REGEX, $value)) {
            return true;
        }

        return false;
    }
}
