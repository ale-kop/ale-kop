<?php

namespace App\Http\Controllers;

use App\Actions\Courses\GrantCourseAccess;
use App\Actions\Courses\GrantFullCourseAccess;
use App\Models\Course;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseAccessController extends Controller
{
    // TODO: gateway — these endpoints currently grant access immediately on POST,
    // with no payment. Before production, route each through the payment gateway
    // and only grant access on a confirmed charge (or gate behind a feature flag).

    public function storeCourse(Request $request, Course $course, GrantCourseAccess $grantCourseAccess): RedirectResponse
    {
        // Course access is sold per year.
        $grantCourseAccess->handle($request->user(), $course, 'checkout', now()->addYear());

        return redirect()
            ->route('courses.show', $course->slug)
            ->with('status', 'Acesso ao curso liberado por 1 ano.');
    }

    public function storeFull(Request $request, GrantFullCourseAccess $grantFullCourseAccess): RedirectResponse
    {
        $grantFullCourseAccess->handle($request->user(), 'checkout', now()->addYear());

        return redirect()
            ->route('dashboard')
            ->with('status', 'Acesso a todos os cursos liberado por 1 ano.');
    }

    public function storePlan(Request $request, Plan $plan, GrantFullCourseAccess $grantFullCourseAccess): RedirectResponse
    {
        abort_unless($plan->is_active, 404);

        $grantFullCourseAccess->handle($request->user(), 'checkout', now()->addYear());

        return redirect()
            ->route('dashboard')
            ->with('status', "Plano “{$plan->name}” ativado. Acesso a todos os cursos por 1 ano.");
    }
}
