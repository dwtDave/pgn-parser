<?php

namespace Tests\Unit;

use dwtie\PgnParser\Validator\ChessNotationValidator;

describe('chess notation validator', static function () {
    it('valid moves', function (string $move) {
        expect(ChessNotationValidator::isValid($move))->toBeTrue();
    })->with('valid_moves');

    it('invalid moves', function (string $move) {
        expect(ChessNotationValidator::isValid($move))->toBeFalse();
    })->with('invalid_moves');
});
