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
    protected array $tags = [];

    /**
     * @var Move[] an array of Move objects
     */
    protected array $moves = [];

    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function addMove(Move $move): void
    {
        $this->moves[] = $move;
    }

    /**
     * @return array<int, Move>
     */
    public function getMoves(): array
    {
        return $this->moves;
    }

    /**
     * @return array<int, Tag>
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
            $tagString .= $tag.PHP_EOL;
        }
        $moveString = '';
        foreach ($this->moves as $move) {
            if ($move->getIsWhiteMove()) {
                $moveString .= $move->getNumber().'. ';
            }
            $moveString .= (string) $move.' ';
        }

        return trim($tagString.$moveString);
    }
}
