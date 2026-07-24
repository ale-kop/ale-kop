<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::query()->firstOrCreate(
            ['email' => 'contato@alekop.com'],
            ['name' => 'Aleksandr', 'password' => 'password']
        );

        $this->call(TagSeeder::class);

        $tag = Tag::query()->first();
        $course = Course::query()->updateOrCreate(
            ['slug' => 'comunicacao-profissional'],
            [
                'name' => 'Comunicação Profissional',
                'slug' => 'comunicacao-profissional',
                'meta' => ['description' => 'Curso base para comunicação profissional.'],
            ]
        );

        collect([
            'Diagnóstico antes da proposta',
            'Follow-up sem pressão',
            'Reunião com próximos passos',
        ])->each(fn (string $name) => Post::query()->updateOrCreate(
            ['slug' => Str::slug($name)],
            [
                'course_id' => $course->id,
                'tag_id' => $tag?->id,
                'user_id' => $user->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'content' => 'Conteúdo de apoio para testes e ambiente local.',
                'meta' => ['description' => $name],
                'extra' => ['featured' => $name === 'Diagnóstico antes da proposta'],
            ]
        ));

        $this->call(NewsletterSeeder::class);
    }
}
