<?php

namespace App\Actions\Courses;

use App\Models\CourseAccess;
use App\Models\User;
use Carbon\CarbonInterface;

class GrantFullCourseAccess
{
    public function handle(User $user, string $source = 'manual', ?CarbonInterface $expiresAt = null): CourseAccess
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
                'expires_at' => $expiresAt,
            ]
        );
    }
}
