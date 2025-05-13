<?php

use HueHue\PgnParser\Struct\Move;
use HueHue\PgnParser\Struct\PGN;

describe('PGN', function () {
    test('pgn to string', function () {
        $pgn = new PGN();
        $pgn->addMove(new Move('d4', 1, true));
        $pgn->addMove(new Move('d5', 2, false));
        $pgn->addMove(new Move('e4', 2, true));

        expect((string) $pgn)->toEqual('1. d4 d5 2. e4');
    });
});
