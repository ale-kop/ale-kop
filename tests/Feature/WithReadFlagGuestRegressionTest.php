<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('guest with read flag keeps base columns selected', function () {
    $this->seed();

    // No explicit select here; previously this would only select `is_read`
    // and drop FK columns like `tag_id`, breaking relations for guests.
    $post = Post::with('tag')->withReadFlag(null)->firstOrFail();

    // Accessing relation should not throw MissingAttributeException
    // (asserting no exception is enough for this regression test)
    $post->tag; // lazy access to ensure FK is present

    expect(array_key_exists('tag_id', $post->getAttributes()))->toBeTrue();
});
