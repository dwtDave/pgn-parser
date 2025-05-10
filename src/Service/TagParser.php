<?php

namespace HueHue\PgnParser\Service;

use HueHue\PgnParser\Struct\Tag;
use HueHue\PgnParser\Validator\TagValidator;
use HueHue\PgnParser\Struct\PGN;

class TagParser implements Parser
{

	/**
	 * Parses a single PGN tag from a string.
	 *
	 * @param mixed $value
	 * @param PGN $pgn
	 * @return void The parsed Tag object.
	 */
	public static function parse(mixed $value, PGN $pgn): void
	{
		preg_match('/\[(\w+)\s"(.*?)"]/', $value, $matches);
		
		$tagName = $matches[1];
		$tagValue = $matches[2];
		
		$pgn->addTag(new Tag($tagName, $tagValue));
	}

	public static function supports(mixed $value): bool
	{
		$tagValidator = new TagValidator();
		
		if (!$tagValidator->isValid($value)) {
			return false;
		}
		
		return true;
	}
}