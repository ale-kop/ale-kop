<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TagController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index-temporary');
})->name('index-temporary');

Route::get('/baixar', [DownloadController::class, 'show'])->name('download.show');
Route::get('/baixar/arquivo', [DownloadController::class, 'stream'])->name('download.stream');

if (config('app.env') === 'local')
    {
        Route::get('/', function () {
    $featuredPost = Post::whereNotNull('course_id')->orWhereNull('course_id')
        ->with('tag')
        ->where('extra->featured', true)
        ->latest()
        ->first();

    $latestTwoPosts = Post::whereNotNull('course_id')->orWhereNull('course_id')
        ->with('tag')
        ->where('extra->featured', false)
        ->latest()
        ->limit(3)
        ->get();

    return view('index', compact('featuredPost', 'latestTwoPosts'));
})->name('index');

Route::get('/index2', function () {
    $featuredPost = Post::with('tag')->where('extra->featured', true)->latest()->first();
    $latestTwoPosts = Post::with('tag')->where('extra->featured', false)->latest()->limit(3)->get();

    return view('index2', compact('featuredPost', 'latestTwoPosts'));
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:6,1');

    Route::get('/register', [RegisterController::class, 'show']);
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:6,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'request']);
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->middleware('throttle:3,1');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout']);
});

// Admin panel (auth required)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminController::class)->name('index');
    Route::get('/posts', [PostController::class, 'manage'])->name('posts');
    Route::get('/tags', [TagController::class, 'index'])->name('tags');
    Route::get('/sections', [SectionController::class, 'index'])->name('sections');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses');
});

// Sections CRUD (no public listing)
Route::resource('sections', SectionController::class)->except(['index', 'show']);

// Tags CRUD (no public listing)
Route::resource('tags', TagController::class)->except(['index', 'show']);

// Posts
Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('posts', [PostController::class, 'store'])->name('posts.store');
Route::get('posts/edit/{post}', [PostController::class, 'edit'])->name('posts.edit');
Route::patch('posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('posts/{post}/read', [PostController::class, 'markRead'])->name('posts.read');
Route::post('posts/{post}/unread', [PostController::class, 'markUnread'])->name('posts.unread');
Route::get('posts-{tagSlug}', [PostController::class, 'index'])->name('posts.index');

// Courses - public listing + CRUD + show
Route::get('cursos', [CourseController::class, 'publicIndex'])->name('courses.index');
Route::get('cursos/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('cursos', [CourseController::class, 'store'])->name('courses.store');
Route::get('cursos/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::patch('cursos/{course}', [CourseController::class, 'update'])->name('courses.update');
Route::delete('cursos/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
Route::get('cursos/{courseSlug}', [CourseController::class, 'show'])->name('courses.show');

// Catch-all post slug must be last
Route::get('{post:slug}', [PostController::class, 'show'])->name('posts.show');
    }
