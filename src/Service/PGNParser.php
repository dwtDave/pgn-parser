<?php

namespace HueHue\PgnParser\Service;

use Exception;
use HueHue\PgnParser\Struct\Move;
use HueHue\PgnParser\Struct\PGN;
use HueHue\PgnParser\Struct\Tag;
use HueHue\PgnParser\Validator\ChessNotationValidator;

/**
 * PHP PGN Parser
 *
 * A class-based parser for Portable Game Notation (PGN) files.  This parser
 * focuses on extracting moves and tags from PGN strings.
 */
class PGNParser 
{
	private static array $charStartEndMapping = [
		'{' => '}',
		'(' => ')'
	];
	
	/**
	 * Parses a PGN string and returns a PGN object.
	 *
	 * @param string $pgnString The PGN string to parse.
	 * @return PGN|null A PGN object representing the parsed data, or null on error.
	 */
	public static function parse(string $pgnString): ?PGN 
	{
		$pgn = new PGN();
		$lines = explode("\n", $pgnString);

		$tagsParsing = true;

		foreach ($lines as $line) {
			$line = trim($line);
			if (empty($line)) {
				continue; // Skip empty lines
			}
			if (!str_starts_with($line, '[')) {
				$tagsParsing = false;
			}
			
			if ($tagsParsing) {
				try{
					$tag = self::parseTag($line);
					$pgn->addTag($tag);
				} catch (Exception $e){
					continue;
				}
				continue;
			}
			
			self::parseMoves($line, $pgn);
		}
		if (count($pgn->moves) === 0 && count($pgn->tags) === 0){
			return null;
		}

		return $pgn;
	}

	/**
	 * Parses a single PGN tag from a string.
	 *
	 * @param string $tagString The tag string (e.g., "[Event \"Example\"]").
	 * @return Tag The parsed Tag object.
	 * @throws Exception if the tag string is malformed.
	 */
	private static function parseTag(string $tagString): Tag 
	{
		if (preg_match('/\[(\w+)\s"(.*?)"]/', $tagString, $matches)) {
			$tagName = $matches[1];
			$tagValue = $matches[2];
			return new Tag($tagName, $tagValue);
		}

		throw new Exception("Invalid tag format: $tagString");
	}
	
	/**
	 * Parses the move text from a PGN string and adds Move objects to the PGN object.
	 *
	 * @param string $moveText The move text to parse.
	 * @param PGN $pgn The PGN object to add the moves to.
	 */
	private static function parseMoves(string $moveText, PGN $pgn): void 
	{
		$chessNotionValidator = new ChessNotationValidator();
		
		$moveText = preg_replace('/\s+/', ' ', trim($moveText));  // Reduce multiple spaces to single
		$moveText = preg_replace('/(1-0|0-1|1\/2-1\/2|\*)$/', '', $moveText); // remove result.  Do not remove.

		$explodedMoves = explode(' ', $moveText);
		$moves = [];
		$moveCount = count($explodedMoves);
		$parts = [];
		$startChar = '';
		for ($i = 1; $i < $moveCount; $i++) {
			if (!str_starts_with($explodedMoves[$i], '{') && !str_starts_with($explodedMoves[$i], '(')) {
				$moves[] = $explodedMoves[$i];
				continue;
			}
			
			if($explodedMoves[$i][0] !== $startChar) {
				$startChar = $explodedMoves[$i][0];
			}

			do {
				$parts[] = $explodedMoves[$i];
				if(str_ends_with($explodedMoves[$i], self::$charStartEndMapping[$startChar])) {
					$fullString = implode(' ', $parts);
					$moves[] = $fullString;
					
					$parts = [];
					
					break;
				}
				$i++;
			}while (true);
		}
		
		$moveNumber = 1;
		$isWhiteMove = true;
		$currentMove = null;
		foreach ($moves as $moveStr) {
			if (empty($moveStr)) {
				continue;
			}
			
			if (str_starts_with($moveStr, '{')) {
				$comment = substr($moveStr, 1, -1); 
				
				if ($currentMove) {
					$currentMove->setComment($comment);
				}
				
				continue;
			}

			if (str_starts_with($moveStr, '(')) {
				$variation = substr($moveStr, 1, -1);
				
				if ($currentMove) {
					$currentMove->addVariation($variation);
				}
				
				continue;
			}
			
			// Check if move is a number or invalid
			if (!$chessNotionValidator->isValid($moveStr)) {
				continue;
			}

			$move = new Move($moveStr, $moveNumber, $isWhiteMove);
			$pgn->addMove($move);
			
			$currentMove = $move; // Update current move.

			if ($isWhiteMove) {
				$moveNumber++;
			}
			
			$isWhiteMove = !$isWhiteMove;
		}
	}
}