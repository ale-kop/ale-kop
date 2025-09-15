<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Section;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy', 'manage', 'markRead']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tagSlug = $request->tagSlug ?? null;
        $tag = $tagSlug ? Tag::where('slug', $tagSlug)->first() : null;
        $userId = auth()->id();
        $posts = $tag
            ? Post::with(['tag:id,name,slug', 'course:id,name,slug'])
                ->withReadFlag($userId, ['posts.id','posts.slug','posts.name','posts.course_id','posts.tag_id','posts.meta'])
                ->where('tag_id', $tag->id)
                ->get()
            : collect();

        if ($request->wantsJson()) {
            return response()->json(['tag' => $tag, 'posts' => $posts]);
        }

        return view('posts.index', compact('posts', 'tag', 'tagSlug'));
    }

    public function manage(Request $request)
    {
        $userId = auth()->id();
        $posts = Post::with(['tag:id,name,slug', 'course:id,name,slug', 'section:id,name,course_id'])
            ->withReadFlag($userId, ['posts.id','posts.slug','posts.name','posts.course_id','posts.tag_id','posts.section_id'])
            ->latest()->paginate(20);

        if ($request->wantsJson()) {
            return response()->json(['posts' => $posts]);
        }

        return view('posts.manage', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = \App\Models\Tag::orderBy('name')->get(['id', 'name']);
        $sections = \App\Models\Section::with('course:id,name')->orderBy('name')->get(['id', 'name', 'course_id']);

        return view('posts.create', compact('tags', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $courseId = Section::find($request->section_id)?->course_id;

        $post = Post::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'content' => $request->content,
            'meta' => $request->meta,
            'course_id' => $courseId,
            'tag_id' => $request->tag_id ?? null,
            'section_id' => $request->section_id ?? null,
            'extra->featured' => isset($request->extra['featured']),
            'user_id' => auth()->id(),
        ]);

        if ($request->featured_image) {
            $post->addMediaFromRequest('featured_image')->toMediaCollection('post-image');
        }

        if ($request->wantsJson()) {
            return response()->json($post, 201);
        }

        return redirect()->route('posts.show', [$post->slug]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): View
    {
        $post = Post::whereSlug($request->post)
            ->with('course', 'course.sections', 'course.sections.posts', 'tag')
            ->firstOrFail();

        if ($post->course) {
            return view('courses.show-post', compact('post'));
        }

        $recentPosts = Post::whereNull('course_id')
            ->where('id', '!=', $post->id)
            ->with('tag')
            ->latest()
            ->limit(20)
            ->get();

        return view('posts.show', compact('post', 'recentPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $tags = \App\Models\Tag::orderBy('name')->get(['id', 'name']);
        $sections = \App\Models\Section::with('course:id,name')->orderBy('name')->get(['id', 'name', 'course_id']);

        return view('posts.edit', compact('post', 'tags', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        $courseId = Section::find($request->section_id)?->course_id;

        $post->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'content' => $request->content,
            'meta' => $request->meta,
            'extra->featured' => isset($request->extra['featured']),
            'course_id' => $courseId,
            'section_id' => $request->section_id ?? null,
            'tag_id' => $request->tag_id ?? null,
        ]);

        if ($request->featured_image) {
            $post->clearMediaCollection('post-image');
            $post->addMediaFromRequest('featured_image')->toMediaCollection('post-image');
        }

        if ($request->wantsJson()) {
            return response()->json($post);
        }

        return redirect()->route('posts.show', [$post->slug]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return back();
    }

    public function markRead(Post $post)
    {
        if (! $post->course_id) {
            return back();
        }

        $user = auth()->user();
        if (! $user->readPosts()->where('post_id', $post->id)->exists()) {
            $user->readPosts()->attach($post->id);
        }

        return back();
    }

    public function markUnread(Post $post)
    {
        if (! $post->course_id) {
            return back();
        }

        $user = auth()->user();
        $user->readPosts()->detach($post->id);

        return back();
    }
}
