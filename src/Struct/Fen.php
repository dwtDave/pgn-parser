<?php

declare(strict_types=1);

namespace HueHue\PgnParser\Struct;

/**
 * Represents a FEN string.
 */
class Fen
{
    public string $piecePlacement;
    public string $activeColor;
    public string $castling;
    public string $enPassant;
    public int $halfmoveClock;
    public int $fullmoveNumber;

    public function __construct(string $fenString)
    {
        $parts = explode(' ', $fenString);
        $this->piecePlacement = $parts[0];
        $this->activeColor = $parts[1] ?? 'w';
        $this->castling = $parts[2] ?? '-';
        $this->enPassant = $parts[3] ?? '-';
        $this->halfmoveClock = (int) ($parts[4] ?? 0);
        $this->fullmoveNumber = (int) ($parts[5] ?? 1);
    }

    public function __toString(): string
    {
        return implode(' ', [
            $this->piecePlacement,
            $this->activeColor,
            $this->castling,
            $this->enPassant,
            $this->halfmoveClock,
            $this->fullmoveNumber,
        ]);
    }
}
