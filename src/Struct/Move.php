<?php

namespace HueHue\PgnParser\Struct;

/**
 * Represents a chess move.
 */
class Move
{
    /**
     * @var string the Standard Algebraic Notation (SAN) representation of the move
     */
    protected string $san;

    /**
     * @var int the move number
     */
    protected int $number;

    /**
     * @var bool true if the move is a white move, false if it's a black move
     */
    protected bool $isWhiteMove;

    /**
     * @var string|null the comment associated with the move, if any
     */
    protected ?string $comment;

    /**
     * @var string|null annotation for good or bad moves
     */
    protected ?string $annotation;

    /**
     * @var string[] any variations associated with the move
     */
    protected array $variations = [];

    /**
     * Constructor.
     *
     * @param string      $san         the SAN representation of the move
     * @param int         $number      the move number
     * @param bool        $isWhiteMove true if the move is a white move, false if black
     * @param string|null $annotation  annotation for good or bad move
     * @param string|null $comment     comment on the move
     */
    public function __construct(string $san, int $number, bool $isWhiteMove, ?string $annotation = null, ?string $comment = null)
    {
        $this->san = $san;
        $this->number = $number;
        $this->isWhiteMove = $isWhiteMove;
        $this->annotation = $annotation;
        $this->comment = $comment;
    }

    public function getIsWhiteMove(): bool
    {
        return $this->isWhiteMove;
    }

    public function setIsWhiteMove(bool $isWhiteMove): void
    {
        $this->isWhiteMove = $isWhiteMove;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * Returns the SAN.
     */
    public function getSan(): string
    {
        return $this->san;
    }

    public function setSan(string $san): void
    {
        $this->san = $san;
    }

    /**
     * Sets the comment for this move.
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Sets the comment for this move.
     *
     * @param string $comment the comment text
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getAnnotation(): ?string
    {
        return $this->annotation;
    }

    public function setAnnotation(?string $annotation): void
    {
        $this->annotation = $annotation;
    }

    /**
     * Adds a variation to this move.
     *
     * @param string $variation the variation text
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

    public function __toString(): string
    {
        $annotation = $this->annotation ?? '';
        if (null !== $this->comment) {
            return sprintf('%s%s {%s}', $this->san, $annotation, $this->comment);
        }

        return $this->san.$annotation;
    }
}
