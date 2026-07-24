<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseAccess extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', 'active')
            ->where(fn (Builder $query) => $query
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>', now()));
    }

    public function scopeFull(Builder $query): Builder
    {
        return $query->where('scope', 'full')->whereNull('course_id');
    }

    public function scopeForCourse(Builder $query, Course|int $course): Builder
    {
        $courseId = $course instanceof Course ? $course->id : $course;

        return $query->where('scope', 'course')->where('course_id', $courseId);
    }
}
