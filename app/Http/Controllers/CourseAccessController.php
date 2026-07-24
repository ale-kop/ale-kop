<?php

namespace App\Http\Controllers;

use App\Actions\Courses\GrantCourseAccess;
use App\Actions\Courses\GrantFullCourseAccess;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseAccessController extends Controller
{
    public function storeCourse(Request $request, Course $course, GrantCourseAccess $grantCourseAccess): RedirectResponse
    {
        $grantCourseAccess->handle($request->user(), $course, 'checkout');

        return redirect()
            ->route('courses.show', $course->slug)
            ->with('status', 'Acesso ao curso liberado.');
    }

    public function storeFull(Request $request, GrantFullCourseAccess $grantFullCourseAccess): RedirectResponse
    {
        $grantFullCourseAccess->handle($request->user(), 'checkout');

        return redirect()
            ->route('dashboard')
            ->with('status', 'Acesso completo liberado.');
    }
}
