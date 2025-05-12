<?php

use HueHue\PgnParser\Struct\Move;
use HueHue\PgnParser\Struct\PGN;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PGN::class)]
class PGNTest extends TestCase
{
    /**
     * Test PGN toString Method.
     */
    public function testPGNToString(): void
    {
        $pgn = new PGN();
        $pgn->addMove(new Move('d4', 1, true));
        $pgn->addMove(new Move('d5', 2, false));
        $pgn->addMove(new Move('e4', 2, true));

        $this->assertEquals('1. d4 d5 2. e4', (string) $pgn);
    }
}
