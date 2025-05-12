<?php

use HueHue\PgnParser\Struct\Tag;

test('tag to string', function () {
    $tag = new Tag('Event', 'Test Event');

    expect((string) $tag)->toEqual('[Event "Test Event"]');
});
