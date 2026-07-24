<?php

use App\Models\Course;
use App\Models\CourseAccess;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function courseWithLesson(string $slug = 'curso-pago'): array
{
    $course = Course::query()->create([
        'name' => str($slug)->replace('-', ' ')->title()->toString(),
        'slug' => $slug,
        'extra' => ['access' => 'paid'],
    ]);

    $post = Post::query()->create([
        'course_id' => $course->id,
        'name' => 'Primeira aula',
        'slug' => $slug.'-primeira-aula',
        'content' => 'Conteúdo restrito.',
    ]);

    return [$course, $post];
}

it('keeps students out of the admin panel', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->get(route('admin.index'))
        ->assertForbidden();
});

it('redirects paid course lessons when the user has no access', function () {
    [$course, $post] = courseWithLesson();
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->get(route('posts.show', $post->slug))
        ->assertRedirect(route('courses.show', $course->slug));
});

it('grants access to one purchased course', function () {
    [$course, $post] = courseWithLesson();
    [$otherCourse, $otherPost] = courseWithLesson('outro-curso');
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->post(route('courses.purchase', $course))
        ->assertRedirect(route('courses.show', $course->slug));

    expect($student->fresh()->canAccessCourse($course))->toBeTrue();
    expect($student->fresh()->canAccessCourse($otherCourse))->toBeFalse();

    $this->actingAs($student)->get(route('posts.show', $post->slug))->assertOk();
    $this->actingAs($student)->get(route('posts.show', $otherPost->slug))->assertRedirect(route('courses.show', $otherCourse->slug));
});

it('grants full access to all paid courses', function () {
    [$course, $post] = courseWithLesson();
    [$otherCourse, $otherPost] = courseWithLesson('outro-curso');
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->post(route('courses.full-access.purchase'))
        ->assertRedirect(route('dashboard'));

    expect($student->fresh()->canAccessCourse($course))->toBeTrue();
    expect($student->fresh()->canAccessCourse($otherCourse))->toBeTrue();

    $this->actingAs($student)->get(route('posts.show', $post->slug))->assertOk();
    $this->actingAs($student)->get(route('posts.show', $otherPost->slug))->assertOk();
});

it('does not allow expired course access', function () {
    [$course, $post] = courseWithLesson();
    $student = User::factory()->create(['role' => 'student']);

    CourseAccess::query()->create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'scope' => 'course',
        'status' => 'active',
        'source' => 'test',
        'purchased_at' => now()->subMonth(),
        'expires_at' => now()->subDay(),
    ]);

    expect($student->fresh()->canAccessCourse($course))->toBeFalse();

    $this->actingAs($student)
        ->get(route('posts.show', $post->slug))
        ->assertRedirect(route('courses.show', $course->slug));
});

it('does not allow cancelled full access', function () {
    [$course, $post] = courseWithLesson();
    $student = User::factory()->create(['role' => 'student']);

    CourseAccess::query()->create([
        'user_id' => $student->id,
        'course_id' => null,
        'scope' => 'full',
        'status' => 'cancelled',
        'source' => 'test',
        'purchased_at' => now()->subMonth(),
    ]);

    expect($student->fresh()->canAccessCourse($course))->toBeFalse();

    $this->actingAs($student)
        ->get(route('posts.show', $post->slug))
        ->assertRedirect(route('courses.show', $course->slug));
});
