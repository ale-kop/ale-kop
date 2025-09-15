<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    public function saved(Post $post): void
    {
        Cache::forget($post->getCacheKeyForPost());
        Cache::forget('tagsWithContent');
        Cache::forget('coursesWithContent');
    }

    public function deleted(Post $post): void
    {
        Cache::forget($post->getCacheKeyForPost());
        Cache::forget('tagsWithContent');
        Cache::forget('coursesWithContent');
    }

    public function restored(Post $post): void
    {
        Cache::forget($post->getCacheKeyForPost());
        Cache::forget('tagsWithContent');
        Cache::forget('coursesWithContent');
    }
}
