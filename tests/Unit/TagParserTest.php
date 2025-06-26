<?php

namespace Tests\Unit;

use dwtie\PgnParser\Parser\TagParser;
use dwtie\PgnParser\Struct\PGN;

describe('TagParser', function () {
    it('supports valid tags', function (string $tagString) {
        expect(TagParser::supports($tagString))->toBeTrue();
    })->with('valid_tag_strings');

    it('does not support invalid tags', function (string $tagString) {
        expect(TagParser::supports($tagString))->toBeFalse();
    })->with('invalid_tag_strings');

    it('parses valid tags', function (string $tagString, string $expectedName, string $expectedValue) {
        $pgn = new PGN();
        TagParser::parse($tagString, $pgn);
        $tags = $pgn->getTags();

        expect($tags)->toHaveCount(1)
            ->and($tags[0]->getName())->toBe($expectedName)
            ->and($tags[0]->getValue())->toBe($expectedValue);
    })->with([
        ['[Event "Test Game"]', 'Event', 'Test Game'],
        ['[Site "My Home"]', 'Site', 'My Home'],
        ['[Date "2024.01.28"]', 'Date', '2024.01.28'],
    ]);
});
