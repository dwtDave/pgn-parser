<?php

use HueHue\PgnParser\Struct\Tag;

dataset('valid_tag_strings', [
    '[Event "Test Game"]',
    '[Site "My Home"]',
    '[Date "2024.01.28"]',
    '[Round "1"]',
    '[White "Player A"]',
    '[Black "Player B"]',
    '[Result "1-0"]',
    '[Result "1/2-1/2"]',
]);

dataset('valid_tags', [
    new Tag('Event', 'Test Event'),
    new Tag('Round', '1'),
    new Tag('White', 'Player A'),
    new Tag('Result', '1/2-1/2'),
]);
