# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack
- Laravel 12+
- Blade (no Vue/React)
- JavaScript Vanilla (no jQuery)
- Tailwind CSS

## Conventions that a like
- Controllers finos: lógica em Services ou Actions
- Queries complexas em Scopes ou Query Builders
- Nomear rotas sempre com `->name()`
- Mobile-first with Tailwind

## What to avoid
- Fat controllers
- `dd()` commited
- Needless Raw queries
- Haeavy login in Blade file

## Commands

**Start dev environment (all services in parallel):**
```bash
composer dev
# Runs: php artisan serve, queue:listen, pail (logs), and vite
```

**Individual services:**
```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1
```

**Build for production:**
```bash
npm run build
php artisan optimize
```

**Run tests:**
```bash
composer test
# or: php vendor/bin/pest

# Single test file:
php vendor/bin/pest tests/Feature/WithReadFlagTest.php

# Single test by name:
php vendor/bin/pest --filter "test name here"
```

**Code style (Laravel Pint):**
```bash
./vendor/bin/pint
```

## Architecture

### Request lifecycle

Every request goes through `bootstrap/app.php`, which registers all custom exception renderers (LoginException, ValidationException, NotFoundHttpException, etc.). Both JSON (`wantsJson()`) and HTML responses are handled there — no need to add try/catch in controllers.

### AJAX pattern

Controllers return JSON automatically when the request includes `Accept: application/json`. The frontend `ajax-post.js` module drives this via `[ak-ajax-click]` attributes. The expected JSON response shape is:

```json
{ "message": "...", "title": "...", "type": "success|warning", "redirect": "...", "modalIdToClose": "...", "reload": 1, "js": "..." }
```

`Toast` and `Modal` are global singletons available in all Blade views — no import needed in JS.

### Caching

`AppServiceProvider` caches `tagsWithContent` and `coursesWithContent` (60-minute TTL) used by layout/header/footer composers. `PostObserver` automatically invalidates these on any post change. If you add new cached keys, register invalidation in `PostObserver`.

### Read-flag pattern

`Post::scopeWithReadFlag($query, ?int $userId)` appends an `is_read` boolean using `withExists` (single subquery, no N+1). Use this scope whenever loading posts for an authenticated user. The flag only applies to course posts (`course_id` is required for mark-as-read).

### Media (Spatie MediaLibrary)

Each model has a single-file collection (e.g., `post-image`, `course-image`). Three conversions are registered: `thumb` (180×180), `medium` (497×290), `large` (1100×500). Access via `$post->image('medium')`.

### JavaScript modules

`app.js` registers modules in `window.globalModules`. After AJAX partial updates, call `window.initListOfModules(['toggle', 'fileUpload'])` to re-initialize only the needed modules. Every module exposes an `init()` function that is idempotent.

### Route ordering

The catch-all `Route::get('{post:slug}', ...)` must remain the **last** route in `web.php`. All other routes must be declared before it.

### Course vs. standalone posts

A post belongs to a course when `course_id` is set (derived from its `section_id`). The `show()` action routes to `courses.show-post` view for course posts and `posts.show` for standalone posts. The `x-post-index` component adapts its rendering to either context automatically.

## Blade components

Custom form components are in `resources/views/components/forms/`. Use them instead of raw HTML inputs:
- `<x-forms.input>`, `<x-forms.textarea>`, `<x-forms.button state="idle">`, `<x-forms.label>`, `<x-forms.file>`, `<x-forms.checkbox>`, `<x-forms.radio-group>`, `<x-forms.radio>`

Icons use Heroicons: `<x-heroicon-o-home class="w-5 h-5" />` (outline) or `<x-heroicon-s-home />` (solid).

Unified post sidebar: `<x-post-index :post="$post" :recentPosts="$recentPosts ?? collect()" />` — renders course index with read/unread badges when `$post->course` exists, or a recent-posts list otherwise.

## Debug tools

`QueryLogger` middleware runs in debug mode and logs all queries. Use `php artisan pail` to tail logs in real time.
