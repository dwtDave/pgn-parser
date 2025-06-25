<?php

namespace Tests\Unit;

use HueHue\PgnParser\Struct\Fen;

describe('FEN', function () {
    it('parses a full FEN string correctly', function () {
        $fenString = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
        $fen = new Fen($fenString);

        expect($fen->piecePlacement)->toBe('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR')
            ->and($fen->activeColor)->toBe('w')
            ->and($fen->castling)->toBe('KQkq')
            ->and($fen->enPassant)->toBe('-')
            ->and($fen->halfmoveClock)->toBe(0)
            ->and($fen->fullmoveNumber)->toBe(1);
    });

    it('handles a FEN string for a different position', function () {
        $fenString = 'rnbq1rk1/pp2ppbp/3p1np1/8/3NP3/2N1BP2/PPP3PP/R2QKB1R b KQ - 2 8';
        $fen = new Fen($fenString);

        expect($fen->piecePlacement)->toBe('rnbq1rk1/pp2ppbp/3p1np1/8/3NP3/2N1BP2/PPP3PP/R2QKB1R')
            ->and($fen->activeColor)->toBe('b')
            ->and($fen->castling)->toBe('KQ')
            ->and($fen->enPassant)->toBe('-')
            ->and($fen->halfmoveClock)->toBe(2)
            ->and($fen->fullmoveNumber)->toBe(8);
    });

    it('converts back to a string correctly', function () {
        $fenString = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
        $fen = new Fen($fenString);
        expect((string) $fen)->toBe($fenString);
    });

    it('handles an incomplete FEN string with default values', function () {
        $fenString = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR';
        $fen = new Fen($fenString);

        expect($fen->activeColor)->toBe('w')
            ->and($fen->castling)->toBe('-')
            ->and($fen->enPassant)->toBe('-')
            ->and($fen->halfmoveClock)->toBe(0)
            ->and($fen->fullmoveNumber)->toBe(1);
    });
});
