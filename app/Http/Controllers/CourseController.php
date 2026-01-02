<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::with('posts', 'sections')->get();

        if ($request->wantsJson()) {
            return response()->json(['courses' => $courses]);
        }

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $course = Course::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'extra->custom_url' => data_get($request->extra, 'custom_url'),
            'meta->description' => data_get($request->meta, 'description'),
        ]);

        if ($request->featured_image) {
            $course->addMediaFromRequest('featured_image')->toMediaCollection('course-image');
        }

        if ($request->wantsJson()) {
            return response()->json($course, 201);
        }

        return redirect()->route('courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): View
    {
        $courseSlug = $request->courseSlug ?? null;
        $userId = auth()->id();
        $course = Course::where('slug', $courseSlug)
            ->with([
                'posts' => fn ($q) => $q->withReadFlag($userId, [
                    'posts.id', 'posts.slug', 'posts.name', 'posts.course_id', 'posts.tag_id', 'posts.meta',
                ]),
                'posts.course:id,slug,name',
                'posts.tag:id,name,slug',
            ])
            ->firstOrFail();

        return view('courses.show', compact('course'));
    }

    public function showPost(Request $request): View
    {
        $post = Post::with('tag', 'course', 'course.sections', 'course.sections.posts')
            ->whereSlug($request->post)
            ->firstOrFail();

        $isRead = null;

        if (\Auth::check()){
            $isRead = $post->is_read ?? $post->isReadBy(auth()->user());
        }

        return view('courses.show-post', compact('post','isRead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
        $course->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'extra->featured' => isset($request->extra['featured']),
            'extra->custom_url' => data_get($request->extra, 'custom_url'),
            'meta->description' => data_get($request->meta, 'description'),
        ]);

        if ($request->featured_image) {
            $course->clearMediaCollection('course-image');
            $course->addMediaFromRequest('featured_image')->toMediaCollection('course-image');
        }

        if ($request->wantsJson()) {
            return response()->json($course);
        }

        return redirect()->route('courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if (Post::where('course_id', $course->id)->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json(['deleted' => false, 'reason' => 'has_posts'], 409);
            }

            return back();
        }

        $course->sections()->delete();
        $course->delete();

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->route('courses.index');
    }
}
