<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $activeAccesses = $user->courseAccesses()->active()->get();
        $hasFullAccess = $user->isAdmin() || $activeAccesses->contains(fn ($access) => $access->scope === 'full' && $access->course_id === null);
        $accessibleCourseIds = $activeAccesses
            ->where('scope', 'course')
            ->pluck('course_id')
            ->filter()
            ->all();

        $courses = Course::query()
            ->with(['posts' => fn ($query) => $query->withReadFlag($user->id)])
            ->orderBy('name')
            ->get()
            ->map(function (Course $course) use ($accessibleCourseIds, $hasFullAccess) {
                $total = $course->posts->count();
                $read = $course->posts->filter(fn ($post) => (bool) ($post->is_read ?? false))->count();

                $course->setAttribute('can_access', $hasFullAccess || $course->isFree() || in_array($course->id, $accessibleCourseIds, true));
                $course->setAttribute('read_count', $read);
                $course->setAttribute('progress_percent', $total > 0 ? round($read / $total * 100) : 0);

                return $course;
            });

        return view('dashboard', [
            'user' => $user,
            'courses' => $courses,
            'hasFullAccess' => $hasFullAccess,
        ]);
    }
}
