<?php

namespace Tests\Unit;

use dwtie\PgnParser\Parser\PGNParser;
use dwtie\PgnParser\Struct\Fen;
use dwtie\PgnParser\Struct\Move;
use dwtie\PgnParser\Struct\PGN;

// Grouping tests related to the PGN __toString() method
describe('PGN __toString', function () {
    test('it converts a simple PGN to a string correctly', function () {
        $pgn = new PGN();
        $pgn->addMove(new Move('d4', 1, true, '!', 'This is a comment'));
        $pgn->addMove(new Move('d5', 1, false));
        $pgn->addMove(new Move('e4', 2, true));

        expect((string) $pgn)->toEqual('1. d4! {This is a comment} d5 2. e4');
    });

    test('it includes the FEN tag in the string output when present', function () {
        $fenString = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
        $pgnString = <<<PGN
[Event "Test Game"]
[FEN "$fenString"]
1. e4 e5
PGN;
        $pgn = PGNParser::parse($pgnString);
        $output = (string) $pgn;

        // The order of tags might vary, so we check for containment
        expect($output)->toContain('[Event "Test Game"]')
            ->and($output)->toContain('[FEN "'.$fenString.'"]')
            ->and($output)->toContain('1. e4 e5');
    });

    test('it does not include a FEN tag in the string output if none was provided', function () {
        $pgnString = <<<PGN
[Event "Test Game"]
1. e4 e5
PGN;
        $pgn = PGNParser::parse($pgnString);
        $output = (string) $pgn;

        expect($output)->not->toContain('[FEN');
    });

    test('it handles a PGN with a variation', function () {
        $pgnString = '1. e4 (1. d4 d5) e5';
        $pgn = PGNParser::parse($pgnString);

        $moves = $pgn->getMoves();
        expect($moves)->toHaveCount(2);
        expect($moves[0]->getSan())->toBe('e4');
        expect($moves[1]->getSan())->toBe('e5');

        $variations = $moves[0]->getVariations();
        expect($variations)->toHaveCount(1);

        $variationPgn = $variations[0];
        expect($variationPgn)->toBeInstanceOf(PGN::class);

        $variationMoves = $variationPgn->getMoves();
        expect($variationMoves)->toHaveCount(2);
        expect($variationMoves[0]->getSan())->toBe('d4');
        expect($variationMoves[1]->getSan())->toBe('d5');
    });

    test('it correctly formats a variation starting with a black move', function () {
        $pgnString = '1. e4 e5 (1... Nf6 2. d3) 2. Nf3 Nc6';
        $pgn = PGNParser::parse($pgnString);

        // The expected string should include the '...' notation for the black move starting the variation.
        $expectedString = '1. e4 e5 (1... Nf6 2. d3) 2. Nf3 Nc6';

        expect((string) $pgn)->toBe($expectedString);
    });

    test('it correctly formats a PGN starting with a black move from a FEN', function () {
        $fenString = 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR b KQkq - 0 1';
        $pgnString = <<<PGN
[FEN "$fenString"]
1... Nf6 2. Nc3
PGN;
        $pgn = PGNParser::parse($pgnString);
        expect((string) $pgn)->toContain('1... Nf6 2. Nc3');
    });
});

// Grouping tests related to parsing and handling FEN data
describe('PGN with FEN parsing', function () {
    it('handles a game with a FEN tag', function () {
        $fenString = 'rnbqkbnr/pp1ppppp/8/2p5/4P3/8/PPPP1PPP/RNBQKBNR w KQkq c6 0 2';
        $pgnString = <<<PGN
[Event "Test Game"]
[FEN "$fenString"]
1. e4 c5
PGN;
        $pgn = PGNParser::parse($pgnString);

        expect($pgn->getFen())->toBeInstanceOf(Fen::class)
            ->and((string) $pgn->getFen())->toBe($fenString);
    });

    it('returns null when no FEN tag is present', function () {
        $pgnString = <<<PGN
[Event "Test Game"]
1. e4 c5
PGN;
        $pgn = PGNParser::parse($pgnString);

        expect($pgn->getFen())->toBeNull();
    });
});
