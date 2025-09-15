<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tags = Tag::withCount('posts')->get();

        if ($request->wantsJson()) {
            return response()->json(['tags' => $tags]);
        }

        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'extra->featured' => isset($request->extra['featured']),
            'extra->custom_url' => data_get($request->extra, 'custom_url'),
            'meta->description' => data_get($request->meta, 'description'),
        ]);

        if ($request->featured_image) {
            $tag->addMediaFromRequest('featured_image')->toMediaCollection('tag-image');
        }

        if ($request->wantsJson()) {
            return response()->json($tag, 201);
        }

        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        return redirect()->route('posts.index', $tag->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'extra->featured' => isset($request->extra['featured']),
            'extra->custom_url' => data_get($request->extra, 'custom_url'),
            'meta->description' => data_get($request->meta, 'description'),
        ]);

        if ($request->featured_image) {
            $tag->clearMediaCollection('tag-image');
            $tag->addMediaFromRequest('featured_image')->toMediaCollection('tag-image');
        }

        if ($request->wantsJson()) {
            return response()->json($tag);
        }

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if (Post::where('tag_id', $tag->id)->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json(['deleted' => false, 'reason' => 'has_posts'], 409);
            }

            return back();
        }

        $tag->delete();

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->route('tags.index');
    }
}
