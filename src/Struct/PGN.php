<?php

declare(strict_types=1);

namespace dwtie\PgnParser\Struct;

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

    /**
     * @var Fen|null the starting position, if specified by a FEN tag
     */
    protected ?Fen $fen = null;

    /**
     * Adds a tag to the PGN object.
     * If the tag is a FEN tag, it also parses it into the dedicated fen property.
     */
    public function addTag(Tag $tag): void
    {
        // Always add the tag to the main tags array to preserve all data.
        $this->tags[] = $tag;

        // If the tag is 'FEN', also create the Fen object for easy access.
        if ('fen' === strtolower($tag->getName())) {
            $this->fen = new Fen($tag->getValue());
        }
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

    public function getFen(): ?Fen
    {
        return $this->fen;
    }

    /**
     * Returns a string representation of the PGN object.
     */
    public function __toString(): string
    {
        $tagString = '';
        // Since all tags (including FEN) are now in the tags array,
        // this single loop is sufficient.
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
