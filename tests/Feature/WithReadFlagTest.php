<?php

use App\Models\Course;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('guest does not expose is_read and query is ok', function () {
    $this->seed();

    $post = Post::whereNotNull('course_id')
        ->select(['posts.id', 'posts.slug', 'posts.name', 'posts.course_id', 'posts.tag_id', 'posts.meta'])
        ->withReadFlag(null)
        ->first();

    expect($post)->not->toBeNull();
    expect(array_key_exists('is_read', $post->getAttributes()))->toBeFalse();
});

it('returns true only for posts read by the user', function () {
    $this->seed();

    $user = User::first();
    $posts = Post::whereNotNull('course_id')->limit(3)->pluck('id');
    expect($posts->count())->toBeGreaterThan(0);

    // Mark first post as read
    $readId = $posts->first();
    $user->readPosts()->syncWithoutDetaching([$readId]);

    $fetched = Post::select(['posts.id', 'posts.slug', 'posts.name', 'posts.course_id', 'posts.tag_id'])
        ->whereIn('id', $posts)
        ->withReadFlag($user->id)
        ->orderBy('id')
        ->get();

    $map = $fetched->keyBy('id');
    expect((bool) $map[$readId]->is_read)->toBeTrue();
    foreach ($posts->skip(1) as $id) {
        expect((bool) $map[$id]->is_read)->toBeFalse();
    }
});

it('works under eager loading for course posts', function () {
    $this->seed();

    $user = User::first();
    $course = Course::with([
        'posts' => fn ($q) => $q
            ->select(['posts.id', 'posts.slug', 'posts.name', 'posts.course_id', 'posts.tag_id', 'posts.section_id'])
            ->withReadFlag($user->id),
    ])->first();

    expect($course)->not->toBeNull();
    expect($course->posts)->not->toBeEmpty();
    foreach ($course->posts as $post) {
        expect((bool) $post->is_read)->toBeBool();
    }
});
