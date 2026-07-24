<?php

namespace App\Actions\Courses;

use App\Models\CourseAccess;
use App\Models\User;

class GrantFullCourseAccess
{
    public function handle(User $user, string $source = 'manual'): CourseAccess
    {
        return $user->courseAccesses()->updateOrCreate(
            [
                'scope' => 'full',
                'course_id' => null,
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
