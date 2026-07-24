<?php

namespace App\Actions\Courses;

use App\Models\Course;
use App\Models\CourseAccess;
use App\Models\User;

class GrantCourseAccess
{
    public function handle(User $user, Course $course, string $source = 'manual'): CourseAccess
    {
        return $user->courseAccesses()->updateOrCreate(
            [
                'scope' => 'course',
                'course_id' => $course->id,
            ],
            [
                'status' => 'active',
                'source' => $source,
                'purchased_at' => now(),
                'expires_at' => null,
            ]
        );
    }
}
