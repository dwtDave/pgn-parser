<?php

namespace Tests\Unit;

use HueHue\PgnParser\Struct\Tag;

describe('moves', function () {
    it('tag to string', function (Tag $tag) {
        $tagString = (string) $tag;
        preg_match('/\[(\w+)\s"(.*?)"]/', $tagString, $matches);
        expect($matches)->toHaveCount(3);
    })->with('valid_tags');

    it('tag getter', function (Tag $tag) {
        $tag->setName('Event');
        $tag->setValue('Test Event');
        $name = $tag->getName();
        $value = $tag->getValue();

        expect($name)->toBe('Event')
            ->and($value)->toBe('Test Event');
    })->with('valid_tags');
});
