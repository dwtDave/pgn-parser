<?php

namespace Tests\Unit;

use HueHue\PgnParser\Parser\PGNParser;
use HueHue\PgnParser\Struct\PGN;

describe('parser', function () {
    it('parse full pgn', function (string $pgnString) {
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(7)
            ->and($pgn?->getMoves())->toHaveCount(44)
            ->and($pgn?->getTags()[0]->getValue())->toEqual('Test Game')
            ->and($pgn?->getTags()[1]->getValue())->toEqual('My Home')
            ->and($pgn?->getTags()[2]->getValue())->toEqual('2024.01.28')
            ->and($pgn?->getMoves()[0]->getSan())->toEqual('e4')
            ->and($pgn?->getMoves()[1]->getSan())->toEqual('c5')
            ->and($pgn?->getMoves()[2]->getSan())->toEqual('Nf3')
            ->and($pgn?->getMoves()[2]->getAnnotation())->toEqual('!')
            ->and($pgn?->getMoves()[5]->getAnnotation())->toEqual('??')
            ->and($pgn?->getMoves()[6]->getAnnotation())->toEqual('!?');
    })->with('valid_full_pgn');

    it('parse empty string', function () {
        $pgnString = '';
        $pgn = PGNParser::parse($pgnString);
        expect($pgn)->toBeNull();
    });

    it('parse invalid tag', function () {
        $pgnString = <<<PGN
		[Event "Test Game"]
		Invalid Tag
		1. e4 c5
		PGN;
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(1)
            ->and($pgn?->getMoves())->toHaveCount(2)
            ->and($pgn?->getMoves()[1]->getSan())->toEqual('c5');
    });
});

describe('tags', function () {
    it('parse pgn with tag', function (string $tag) {
        $pgn = PGNParser::parse($tag);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(1)
            ->and($pgn?->getMoves())->toHaveCount(0);
    })->with('valid_tag_strings');
});

describe('moves', function () {
    it('parse only moves', function () {
        $pgnString = '1. e4 c5 2. Nf3 d6 3. d4';
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(0)
            ->and($pgn?->getMoves())->toHaveCount(5)
            ->and($pgn?->getMoves()[0]->getSan())->toEqual('e4')
            ->and($pgn?->getMoves()[1]->getSan())->toEqual('c5')
            ->and($pgn?->getMoves()[2]->getSan())->toEqual('Nf3');
    });

    it('parse moves with comment', function () {
        $pgnString = '1. e4 c5 {This is a comment} 2. Nf3 d6 3. d4';
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(0)
            ->and($pgn?->getMoves())->toHaveCount(5)
            ->and($pgn?->getMoves()[1]->getComment())->toEqual('This is a comment');
    });

    it('parse moves with comments', function () {
        $pgnString = '1. e4 c5 {This is a comment} 2. Nf3 d6 {This is a comment} 3. d4';
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(0)
            ->and($pgn?->getMoves())->toHaveCount(5)
            ->and($pgn?->getMoves()[1]->getComment())->toEqual('This is a comment')
            ->and($pgn?->getMoves()[3]->getComment())->toEqual('This is a comment');
    });
});
