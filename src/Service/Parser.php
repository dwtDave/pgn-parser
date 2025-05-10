<?php

namespace HueHue\PgnParser\Service;

use HueHue\PgnParser\Struct\PGN;

interface Parser
{
	public static function parse(mixed $value, PGN $pgn): void;
	
	public static function supports(mixed $value): bool;
}