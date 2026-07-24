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
        $courses = Course::query()
            ->with(['posts' => fn ($query) => $query->withReadFlag($user->id)])
            ->orderBy('name')
            ->get()
            ->map(function (Course $course) use ($user) {
                $total = $course->posts->count();
                $read = $course->posts->filter(fn ($post) => (bool) ($post->is_read ?? false))->count();

                $course->setAttribute('can_access', $user->canAccessCourse($course));
                $course->setAttribute('read_count', $read);
                $course->setAttribute('progress_percent', $total > 0 ? round($read / $total * 100) : 0);

                return $course;
            });

        return view('dashboard', [
            'user' => $user,
            'courses' => $courses,
            'hasFullAccess' => $user->hasFullCourseAccess(),
        ]);
    }
}
