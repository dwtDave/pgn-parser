<?php

declare(strict_types=1);

namespace dwtie\PgnParser\Struct;

/**
 * Represents a PGN tag.
 */
class Tag
{
    /**
     * @var string the name of the tag
     */
    protected string $name;

    /**
     * @var string the value of the tag
     */
    protected string $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return sprintf('[%s "%s"]', $this->name, $this->value);
    }
}
