<?php

use App\Models\Course;
use App\Models\CourseAccess;
use App\Models\Plan;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function courseWithLesson(string $slug = 'curso-pago', string $access = 'paid'): array
{
    $course = Course::query()->create([
        'name' => str($slug)->replace('-', ' ')->title()->toString(),
        'slug' => $slug,
        'extra' => ['access' => $access, 'price' => 97.00],
    ]);

    $post = Post::query()->create([
        'course_id' => $course->id,
        'name' => 'Primeira aula',
        'slug' => $slug.'-primeira-aula',
        'content' => 'Conteúdo restrito.',
    ]);

    return [$course, $post];
}

it('treats courses as free unless explicitly marked paid', function () {
    $course = Course::query()->create(['name' => 'Curso Livre', 'slug' => 'curso-livre']);
    $post = Post::query()->create([
        'course_id' => $course->id,
        'name' => 'Aula aberta',
        'slug' => 'curso-livre-aula-aberta',
        'content' => 'Conteúdo livre.',
    ]);
    $student = User::factory()->create(['role' => 'student']);

    expect($course->isFree())->toBeTrue();
    expect($student->canAccessCourse($course))->toBeTrue();

    // Registered user reads the free lesson.
    $this->actingAs($student)->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('Conteúdo livre.');
});

it('locks a free lesson behind registration for guests', function () {
    $course = Course::query()->create(['name' => 'Curso Livre', 'slug' => 'curso-livre']);
    $post = Post::query()->create([
        'course_id' => $course->id,
        'name' => 'Aula aberta',
        'slug' => 'curso-livre-aula-aberta',
        'content' => 'Conteúdo livre.',
    ]);

    $this->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('Criar conta e assistir')
        ->assertDontSee('Conteúdo livre.');
});

it('keeps students out of the admin panel', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->get(route('admin.index'))
        ->assertForbidden();
});

it('shows the paid lock page instead of the lesson when the user has no access', function () {
    [$course, $post] = courseWithLesson();
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->get(route('posts.show', $post->slug))
        ->assertOk()
        ->assertSee('Curso com acesso pago')
        ->assertSee('Comprar este curso')
        ->assertDontSee('Conteúdo restrito.');
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

    $this->actingAs($student)->get(route('posts.show', $post->slug))->assertOk()->assertSee('Conteúdo restrito.');
    $this->actingAs($student)->get(route('posts.show', $otherPost->slug))->assertOk()->assertSee('Curso com acesso pago');
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

    $this->actingAs($student)->get(route('posts.show', $post->slug))->assertOk()->assertSee('Conteúdo restrito.');
    $this->actingAs($student)->get(route('posts.show', $otherPost->slug))->assertOk()->assertSee('Conteúdo restrito.');
});

it('grants full access when subscribing to an active plan', function () {
    [$course, $post] = courseWithLesson();
    $plan = Plan::query()->create([
        'name' => 'Full Mensal',
        'price' => 49.00,
        'interval' => 'month',
        'scope' => 'full',
        'is_active' => true,
    ]);
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student)
        ->post(route('plans.subscribe', $plan))
        ->assertRedirect(route('dashboard'));

    expect($student->fresh()->canAccessCourse($course))->toBeTrue();
});

it('registering from the lock page returns the user to the lesson', function () {
    $course = Course::query()->create(['name' => 'Curso Livre', 'slug' => 'curso-livre']);
    $post = Post::query()->create([
        'course_id' => $course->id,
        'name' => 'Aula aberta',
        'slug' => 'curso-livre-aula-aberta',
        'content' => 'Conteúdo livre.',
    ]);

    $this->post('/register', [
        'name' => 'Nova Pessoa',
        'email' => 'nova@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'redirect' => route('posts.show', $post->slug),
    ])->assertRedirect(route('posts.show', $post->slug));

    $this->assertAuthenticated();
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
        ->assertOk()
        ->assertSee('Curso com acesso pago')
        ->assertDontSee('Conteúdo restrito.');
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
        ->assertOk()
        ->assertSee('Curso com acesso pago')
        ->assertDontSee('Conteúdo restrito.');
});
