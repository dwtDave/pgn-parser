<?php

namespace HueHue\PgnParser\Struct;

/**
 * Represents a PGN tag.
 */
class Tag {
	/**
	 * @var string The name of the tag.
	 */
	public string $name;

	/**
	 * @var string The value of the tag.
	 */
	public string $value;

	/**
	 * Constructor.
	 *
	 * @param string $name The name of the tag.
	 * @param string $value The value of the tag.
	 */
	public function __construct(string $name, string $value) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Returns a string representation of the tag.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return sprintf('[%s "%s"]', $this->name, $this->value);
	}
}