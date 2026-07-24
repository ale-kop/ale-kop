<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseAccessController extends Controller
{
    public function storeCourse(Request $request, Course $course): RedirectResponse
    {
        $request->user()->grantCourseAccess($course, 'checkout');

        return redirect()
            ->route('courses.show', $course->slug)
            ->with('status', 'Acesso ao curso liberado.');
    }

    public function storeFull(Request $request): RedirectResponse
    {
        $request->user()->grantFullCourseAccess('checkout');

        return redirect()
            ->route('dashboard')
            ->with('status', 'Acesso completo liberado.');
    }
}
