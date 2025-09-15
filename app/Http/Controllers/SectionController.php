<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Course;
use App\Models\Post;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sections = Section::with('course')->withCount('posts')->get();

        if ($request->wantsJson()) {
            return response()->json(['sections' => $sections]);
        }

        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('name')->get(['id', 'name']);

        return view('sections.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
        $section = Section::create([
            'name' => $request->name,
            'course_id' => $request->course_id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($section, 201);
        }

        return redirect()->route('sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        return redirect()->route('courses.show', $section->course->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $courses = Course::orderBy('name')->get(['id', 'name']);

        return view('sections.edit', compact('section', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, Section $section)
    {
        $section->update([
            'name' => $request->name,
            'course_id' => $request->course_id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($section);
        }

        return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        if (Post::where('section_id', $section->id)->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json(['deleted' => false, 'reason' => 'has_posts'], 409);
            }

            return back();
        }

        $section->delete();

        if (request()->wantsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()->route('sections.index');
    }
}
