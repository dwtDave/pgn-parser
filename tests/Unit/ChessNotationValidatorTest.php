<?php

namespace Tests;

use HueHue\PgnParser\Validator\ChessNotationValidator;

describe('chess notation validator', static function () {
    it('valid moves', function (string $move) {
        expect(ChessNotationValidator::isValid($move))->toBeTrue();
    })->with([
        'd4',
        'O-O',
        'e8=Q',
        'Rdf8',
        'gxh8=Q',
        'Nf6xe4',
        'Nf6xe4?',
        'Nf6xe4?!',
        'e4!!',
        'e4!?',
    ]);

    it('invalid moves', function (string $move) {
        expect(ChessNotationValidator::isValid($move))->ToBeFalse();
    })->with([
        'ee3',
        'j4',
        '!e3',
    ]);
});
