<?php

namespace HueHue\PgnParser\Validator;

final class ChessNotationValidator implements ValidatorInterface
{
    public static function isValid(mixed $value): bool
    {
        $value = trim($value);

        // Basic piece moves (e.g., Ne5, Ra1, Qf3)
        if (preg_match('/^[KQRBN][a-h][1-8][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        // Piece captures (e.g., Qxe7, Rxf5)
        if (preg_match('/^[KQRBN]x[a-h][1-8][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        // Pawn moves (e.g., e4, d5)
        if (preg_match('/^[a-h][1-8][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        // Pawn captures (e.g., exd5, bxa6)
        if (preg_match('/^[a-h]x[a-h][1-8][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        // Pawn promotions (e.g., e8=Q, d8=N+)
        if (preg_match('/^[a-h][1-8]=[QRBN][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }
        // Promotion after capture
        if (preg_match('/^[a-h]x[a-h][1-8]=[QRBN][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        // Castling
        if (in_array($value, ['O-O', 'O-O-O'])) {
            return true;
        }

        // Disambiguation (e.g., Rae1, Nfd2)
        if (preg_match('/^[KQRBN][a-h1-8]?[a-h][1-8][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        // Disambiguation with capture
        if (preg_match('/^[KQRBN][a-h][1-8]x[a-h][1-8][+#]?[!?]{0,2}$/', $value)) {
            return true;
        }

        return false;
    }
}
