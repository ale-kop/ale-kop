<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReadPostsSeeder extends Seeder
{
    public function run(): void
    {
        // Avoid introducing nondeterminism during tests: skip random read marks in testing
        if (app()->environment('testing')) {
            return;
        }

        $user = User::where('email', 'test@example.com')->first() ?: User::first();
        if (! $user) {
            return;
        }

        $postIds = Post::whereNotNull('course_id')
            ->inRandomOrder()
            ->limit(3)
            ->pluck('id')
            ->all();

        if (empty($postIds)) {
            return;
        }

        $user->readPosts()->syncWithoutDetaching($postIds);
    }
}
