<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Post;
use App\Models\Tag;
use App\Observers\PostObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        setlocale(LC_TIME, config('app.locale'));
        Carbon::setLocale(config('app.locale'));

        Model::shouldBeStrict(! $this->app->isProduction());

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // View composers: provide cached tags and courses with content
        view()->composer([
            'components.header',
            'components.footer',
            'index',
            'posts.index',
            'components.mobile-menu',
        ], function ($view) {
            $tagsWithContent = cache()->remember(
                'tagsWithContent',
                Carbon::now()->addMinutes(60),
                fn () => Tag::query()->whereHas('posts')->get()
            );

            $coursesWithContent = cache()->remember(
                'coursesWithContent',
                Carbon::now()->addMinutes(60),
                fn () => Course::query()->whereHas('posts')->get()
            );

            return $view->with([
                'tagsWithContent' => $tagsWithContent,
                'coursesWithContent' => $coursesWithContent,
            ]);
        });

        // Observe post changes to invalidate caches
        Post::observe(PostObserver::class);
    }
}
