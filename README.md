# PHP PGN Parser
[![Latest Stable Version](http://poser.pugx.org/huehue/pgn-parser/v)](https://packagist.org/packages/huehue/pgn-parser)
[![Total Downloads](http://poser.pugx.org/huehue/pgn-parser/downloads)](https://packagist.org/packages/huehue/pgn-parser)
[![License](http://poser.pugx.org/huehue/pgn-parser/license)](https://packagist.org/packages/huehue/pgn-parser)

A PHP library for parsing Portable Game Notation (PGN) files. This parser is designed to extract chess game metadata, moves, and variations from PGN strings.

## Features

* **PGN Parsing:** Parses PGN formatted strings.
* **Tag Extraction:** Extracts tag pairs (e.g., `[Event "Chess Game"]`, `[Site "Home"]`).
* **Move Parsing:** Parses chess moves, including SAN notation.
* **Comments:** Handles move comments (e.g., `1. e4 {This is a comment}`).
* **Variations:** Parses move variations (e.g., `1. e4 (1... c5 2. Nf3)`).
* **Object-Oriented:** Uses a class-based structure with `PGN`, `Tag`, and `Move` objects.

## Installation

The preferred method of installation is via [Composer](https://getcomposer.org/):