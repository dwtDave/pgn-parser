<?php

use HueHue\PgnParser\Service\PGNParser;
use HueHue\PgnParser\Struct\PGN;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PGNParser::class)]
class PGNParserTest extends TestCase
{
	/**
	 * Test successful parsing of a PGN string with tags and moves.
	 */
	public function testParseSuccess(): void
	{
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

		$this->assertInstanceOf(PGN::class, $pgn);
		$this->assertCount(7, $pgn->getTags());
		$this->assertCount(44, $pgn->getMoves());

		$this->assertEquals('Test Game', $pgn->getTags()[0]->value);
		$this->assertEquals('My Home', $pgn->getTags()[1]->value);
		$this->assertEquals('2024.01.28', $pgn->getTags()[2]->value);

		$this->assertEquals('e4', $pgn->getMoves()[0]->getSan());
		$this->assertEquals('c5', $pgn->getMoves()[1]->getSan());
		$this->assertEquals('Nf3', $pgn->getMoves()[2]->getSan());
	}

    /**
     * Test parsing of the result tag with a remi result
     */
    public function testParseResultRemi(): void
    {
        $pgnString = <<<PGN
		[Result "1/2-1/2"]		
		PGN;
        $pgn = PGNParser::parse($pgnString);

        $this->assertInstanceOf(PGN::class, $pgn);
        $this->assertCount(1, $pgn->getTags());

        $this->assertEquals('1/2-1/2', $pgn->getTags()[0]->value);
    }

	/**
	 * Test parsing of a PGN string with only tags.
	 */
	public function testParseOnlyTags(): void
	{
		$pgnString = <<<PGN
		[Event "Test Game"]
		[Site "My Home"]
		[Date "2024.01.28"]
		PGN;
		$pgn = PGNParser::parse($pgnString);

		$this->assertInstanceOf(PGN::class, $pgn);
		$this->assertCount(3, $pgn->getTags());
		$this->assertCount(0, $pgn->getMoves());

		$this->assertEquals('Test Game', $pgn->getTags()[0]->value);
		$this->assertEquals('My Home', $pgn->getTags()[1]->value);
		$this->assertEquals('2024.01.28', $pgn->getTags()[2]->value);
	}

	/**
	 * Test parsing of a PGN string with only moves.
	 */
	public function testParseOnlyMoves(): void
	{
		$pgnString = "1. e4 c5 2. Nf3 d6 3. d4";
		$pgn = PGNParser::parse($pgnString);

		$this->assertInstanceOf(PGN::class, $pgn);
		$this->assertCount(0, $pgn->getTags());
		$this->assertCount(5, $pgn->getMoves());

		$this->assertEquals('e4', $pgn->getMoves()[0]->getSan());
		$this->assertEquals('c5', $pgn->getMoves()[1]->getSan());
		$this->assertEquals('Nf3', $pgn->getMoves()[2]->getSan());
	}

	/**
	 * Test parsing of a PGN string with moves and a comment.
	 */
	public function testParseMovesWithComment(): void
	{
		$pgnString = "1. e4 c5 {This is a comment} 2. Nf3 d6 3. d4";
		$pgn = PGNParser::parse($pgnString);

		$this->assertInstanceOf(PGN::class, $pgn);
		$this->assertCount(0, $pgn->getTags());
		$this->assertCount(5, $pgn->getMoves());
		
		$this->assertEquals('This is a comment', $pgn->getMoves()[1]->getComment());
	}

	/**
	 * Test parsing of a PGN string with moves and comments.
	 */
	public function testParseMovesWithComments(): void
	{
		$pgnString = "1. e4 c5 {This is a comment} 2. Nf3 d6 {This is a comment} 3. d4";
		$pgn = PGNParser::parse($pgnString);

		$this->assertInstanceOf(PGN::class, $pgn);
		$this->assertCount(0, $pgn->getTags());
		$this->assertCount(5, $pgn->getMoves());
		
		$this->assertEquals('This is a comment', $pgn->getMoves()[1]->getComment());
		$this->assertEquals('This is a comment', $pgn->getMoves()[3]->getComment());
	}

    /**
     * Test parsing of a PGN string with moves that have a ! for a good move.
     */
    public function testParseMovesWithGoodMoveMarker(): void
    {
        $pgnString = "1. e4 c5! 2. Nf3 d6";
        $pgn = PGNParser::parse($pgnString);

        $this->assertInstanceOf(PGN::class, $pgn);
        $this->assertCount(0, $pgn->getTags());
        $this->assertCount(4, $pgn->getMoves());

        $this->assertEquals('c5!', $pgn->getMoves()[1]->getSan());
    }

	/**
	 * Test parsing of an empty PGN string.
	 */
	public function testParseEmptyString(): void
	{
		$pgnString = "";
		$pgn = PGNParser::parse($pgnString);
		$this->assertNull($pgn);
	}

	/**
	 * Test parsing of a PGN string with invalid tag.
	 */
	public function testParseInvalidTag(): void
	{
		$pgnString = <<<PGN
		[Event "Test Game"]
		Invalid Tag
		1. e4 c5
		PGN;
		$pgn = PGNParser::parse($pgnString);

		$this->assertInstanceOf(PGN::class, $pgn);
		$this->assertCount(1, $pgn->getTags());
		$this->assertCount(2, $pgn->getMoves());
		$this->assertEquals("c5", $pgn->getMoves()[1]->getSan());
	}
}
