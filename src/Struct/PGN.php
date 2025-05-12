<?php

namespace HueHue\PgnParser\Struct;

/**
 * Represents a parsed PGN game.
 */
class PGN
{
    /**
     * @var Tag[] an array of Tag objects
     */
    public array $tags = [];

    /**
     * @var Move[] an array of Move objects
     */
    public array $moves = [];

    /**
     * Adds a tag to the PGN game.
     *
     * @param Tag $tag the Tag object to add
     */
    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    /**
     * Adds a move to the PGN game.
     *
     * @param Move $move the Move object to add
     */
    public function addMove(Move $move): void
    {
        $this->moves[] = $move;
    }

    /**
     * Returns the moves.
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    /**
     * Returns the tags.
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Returns a string representation of the PGN object.
     */
    public function __toString(): string
    {
        $tagString = '';
        foreach ($this->tags as $tag) {
            $tagString .= $tag."\n";
        }
        $moveString = '';
        foreach ($this->moves as $move) {
            $moveString .= $move->san.' ';
        }

        return $tagString.$moveString;
    }
}
