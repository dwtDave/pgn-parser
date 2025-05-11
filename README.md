# PGN Parser

[![Latest Stable Version](http://poser.pugx.org/huehue/pgn-parser/v)](https://packagist.org/packages/huehue/pgn-parser)
[![Total Downloads](http://poser.pugx.org/huehue/pgn-parser/downloads)](https://packagist.org/packages/huehue/pgn-parser)
[![License](http://poser.pugx.org/huehue/pgn-parser/license)](https://packagist.org/packages/huehue/pgn-parser)

A PHP library for parsing Portable Game Notation (PGN) files. This library is designed to extract chess game metadata and moves from PGN strings. It validates move syntax, including support for move annotations.

## Features

* **PGN Parsing:** Parses PGN formatted strings.
* **Tag Extraction:** Extracts tag pairs (e.g., `[Event "Chess Game"]`, `[Site "Home"]`).
* **Move Parsing:** Parses chess moves, including SAN notation.
* **Move Validation:** Validates move syntax, including:
    * Basic piece moves (e.g., Ne5, Ra1, Qf3)
    * Piece captures (e.g., Qxe7, Rxf5)
    * Pawn moves (e.g., e4, d5)
    * Pawn captures (e.g., exd5, bxa6)
    * Pawn promotions (e.g., e8=Q, d8=N+)
    * Castling (`O-O`, `O-O-O`)
    * Disambiguation (e.g., Rae1, Nfd2)
    * Move annotations/evaluation symbols (+, #, !, ?, !!, ??, !?, ?!)

## Installation

The preferred method of installation is via [Composer](https://getcomposer.org/):

```bash
composer require huehue/pgn-parser
```