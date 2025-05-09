<?php

namespace HueHue\PgnParser\Struct;

/**
 * Represents a parsed PGN game.
 */
class PGN {
	/**
	 * @var Tag[] An array of Tag objects.
	 */
	public array $tags = [];

	/**
	 * @var Move[] An array of Move objects.
	 */
	public array $moves = [];

	/**
	 * Adds a tag to the PGN game.
	 *
	 * @param Tag $tag The Tag object to add.
	 */
	public function addTag(Tag $tag): void {
		$this->tags[] = $tag;
	}

	/**
	 * Adds a move to the PGN game.
	 *
	 * @param Move $move The Move object to add.
	 */
	public function addMove(Move $move): void {
		$this->moves[] = $move;
	}

	/**
	 * Returns the moves.
	 *
	 * @return array
	 */
	public function getMoves(): array
	{
		return $this->moves;
	}

	/**
	 * Returns the tags
	 *
	 * @return array
	 */
	public function getTags(): array
	{
		return $this->tags;
	}

	/**
	 * Returns a string representation of the PGN object.
	 *
	 * @return string
	 */
	public function __toString(): string {
		$tagString = "";
		foreach ($this->tags as $tag) {
			$tagString .= $tag . "\n";
		}
		$moveString = "";
		foreach($this->moves as $move){
			$moveString .= $move->san . " ";
		}
		return $tagString . $moveString;
	}
}