<?php

use HueHue\PgnParser\Struct\Tag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Tag::class)]
class TagTest extends TestCase
{
    /**
     * Test Tag toString Method.
     */
    public function testTagToString(): void
    {
        $tag = new Tag('Event', 'Test Event');

        $this->assertEquals('[Event "Test Event"]', (string) $tag);
    }
}
