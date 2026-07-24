<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'contacts' => 'array',
            'links' => 'array',
            'info' => 'array',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function readPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function courseAccesses(): HasMany
    {
        return $this->hasMany(CourseAccess::class);
    }

    public function accessibleCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_accesses')
            ->wherePivot('scope', 'course')
            ->wherePivot('status', 'active')
            ->withPivot(['source', 'purchased_at', 'expires_at'])
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function hasFullCourseAccess(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->courseAccesses()
            ->active()
            ->full()
            ->exists();
    }

    public function canAccessCourse(Course $course): bool
    {
        if ($this->hasFullCourseAccess()) {
            return true;
        }

        if ($course->isFree()) {
            return true;
        }

        return $this->courseAccesses()
            ->active()
            ->forCourse($course)
            ->exists();
    }
}
