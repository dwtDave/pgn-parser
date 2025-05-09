<?php

namespace HueHue\PgnParser\Struct;

/**
 * Represents a chess move.
 */
class Move 
{
	/**
	 * @var string The Standard Algebraic Notation (SAN) representation of the move.
	 */
	public string $san;

	/**
	 * @var int The move number.
	 */
	public int $number;

	/**
	 * @var bool True if the move is a white move, false if it's a black move.
	 */
	public bool $isWhiteMove;

	/**
	 * @var string|null  The comment associated with the move, if any.
	 */
	public ?string $comment;

	/**
	 * @var string[]  Any variations associated with the move.
	 */
	public array $variations = [];
	
	/**
	 * Constructor.
	 *
	 * @param string $san The SAN representation of the move.
	 * @param int $number The move number.
	 * @param bool $isWhiteMove True if the move is a white move, false if black.
	 * @param string|null $comment Comment on the move.
	 */
	public function __construct(string $san, int $number, bool $isWhiteMove, string $comment = null) 
	{
		$this->san = $san;
		$this->number = $number;
		$this->isWhiteMove = $isWhiteMove;
		$this->comment = $comment;
	}

	/**
	 * Returns the move number
	 *
	 * @return int
	 */
	public function getNumber(): int
	{
		return $this->number;
	}

	/**
	 * Returns the SAN
	 *
	 * @return string
	 */
	public function getSan(): string
	{
		return $this->san;
	}
	
	/**
	 * Sets the comment for this move.
	 *
	 * @return string
	 */
	public function getComment(): string
	{
		return $this->comment;
	}
	
	/**
	 * Adds a variation to this move.
	 *
	 * @param string $variation The variation text.
	 */
	public function addVariation(string $variation): void 
	{
		$this->variations[] = $variation;
	}

	/**
	 * Gets the variations for this move.
	 *
	 * @return string[]
	 */
	public function getVariations(): array 
	{
		return $this->variations;
	}

	/**
	 * Returns a string representation of the move.
	 *
	 * @return string
	 */
	public function __toString(): string 
	{
		$moveString = ($this->isWhiteMove ? $this->number . ". " : $this->number . "...") . $this->san;
		if ($this->comment) {
			$moveString .= " {{$this->comment}}";
		}
		if (!empty($this->variations)) {
			$moveString .= " (" . implode(") (", $this->variations) . ")";
		}
		return $moveString;
	}
}