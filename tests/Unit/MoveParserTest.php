<?php

namespace Tests\Unit;

use HueHue\PgnParser\Parser\MoveParser;

class MoveParserProxy extends MoveParser
{
    /**
     * @param array<int, string> $notPreparedMoves
     *
     * @return array<int, string>
     */
    public static function callRecombineVariationAndComments(array $notPreparedMoves): array
    {
        return self::recombineVariationAndComments($notPreparedMoves);
    }
}

describe('MoveParser', function () {
    it('supports valid move strings', function (string $moveString) {
        expect(MoveParser::supports($moveString))->toBeTrue();
    })->with('valid_move_strings_for_supports');

    it('does not support invalid move strings', function (string $moveString) {
        expect(MoveParser::supports($moveString))->toBeFalse();
    })->with('invalid_move_strings_for_supports');

    it('recombines variations and comments', function (array $input, array $expected) {
        $result = MoveParserProxy::callRecombineVariationAndComments($input);
        expect($result)->toEqual($expected);
    })->with('recombine_variations_and_comments');
});
