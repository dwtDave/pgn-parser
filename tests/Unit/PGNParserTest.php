<?php

use HueHue\PgnParser\Service\PGNParser;
use HueHue\PgnParser\Struct\PGN;

describe('parser', function () {
    it('parse success', function () {
        $pgnString = <<<PGN
		[Event "Test Game"]
		[Site "My Home"]
		[Date "2024.01.28"]
		[Round "?"]
		[White "Player A"]
		[Black "Player B"]
		[Result "1-0"]
		
		1. e4 c5 2. Nf3 d6 3. d4 cxd4 4. Nxd4 Nf6 5. Nc3 a6 6. Be2 e6 7. O-O Be7 8. f4 O-O 9. Kh1 Nc6 10. Be3 Qc7 11. a4 Bd7 12. Qe1 Nxd4 13. Bxd4 Bc6 14. Bd3 Nd7 15. Qg3 g6 16. f5 exf5 17. exf5 Ne5 18. Rae1 Rae8 19. Bxe5 dxe5 20. fxg6 hxg6 21. Bxg6 fxg6 22. Qxg6+ Kh8 
		PGN;
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(7)
            ->and($pgn?->getMoves())->toHaveCount(44)
            ->and($pgn?->getTags()[0]->value)->toEqual('Test Game')
            ->and($pgn?->getTags()[1]->value)->toEqual('My Home')
            ->and($pgn?->getTags()[2]->value)->toEqual('2024.01.28')
            ->and($pgn?->getMoves()[0]->getSan())->toEqual('e4')
            ->and($pgn?->getMoves()[1]->getSan())->toEqual('c5')
            ->and($pgn?->getMoves()[2]->getSan())->toEqual('Nf3');
    });

    it('parse result remi', function () {
        $pgnString = <<<PGN
		[Result "1/2-1/2"]		
		PGN;
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(1)
            ->and($pgn?->getTags()[0]->value)->toEqual('1/2-1/2');
    });

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
    it('parse only tags', function () {
        $pgnString = <<<PGN
		[Event "Test Game"]
		[Site "My Home"]
		[Date "2024.01.28"]
		PGN;
        $pgn = PGNParser::parse($pgnString);

        expect($pgn)->toBeInstanceOf(PGN::class)
            ->and($pgn?->getTags())->toHaveCount(3)
            ->and($pgn?->getMoves())->toHaveCount(0)
            ->and($pgn?->getTags()[0]->value)->toEqual('Test Game')
            ->and($pgn?->getTags()[1]->value)->toEqual('My Home')
            ->and($pgn?->getTags()[2]->value)->toEqual('2024.01.28');
    });
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
