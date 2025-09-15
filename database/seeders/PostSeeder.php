<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Post;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $postsPlan = [
            'laravel-basico' => [
                'Introdução' => [
                    ['name' => 'Bem-vindo ao Laravel', 'content' => 'Instalação e primeiros passos.'],
                    ['name' => 'Estrutura de Diretórios', 'content' => 'Conheça o esqueleto do projeto.'],
                ],
                'Rotas e Controllers' => [
                    ['name' => 'Definindo Rotas', 'content' => 'GET, POST e nomeação de rotas.'],
                ],
            ],
            'laravel-avancado' => [
                'Arquitetura' => [
                    ['name' => 'Services e Actions', 'content' => 'Organizando a regra de negócio.'],
                ],
            ],
            'frontend-com-tailwind' => [
                'Fundamentos' => [
                    ['name' => 'Começando com Tailwind 4', 'content' => 'Configuração via Vite.'],
                ],
            ],
        ];

        foreach ($postsPlan as $courseSlug => $sections) {
            $course = Course::where('slug', $courseSlug)->first();
            if (! $course) {
                continue;
            }
            foreach ($sections as $sectionName => $posts) {
                $section = Section::where('course_id', $course->id)->where('name', $sectionName)->first();
                if (! $section) {
                    continue;
                }
                foreach ($posts as $data) {
                    Post::updateOrCreate(
                        ['slug' => Str::slug($data['name'])],
                        [
                            'name' => $data['name'],
                            'slug' => Str::slug($data['name']),
                            'content' => $data['content'],
                            'user_id' => $user?->id,
                            'course_id' => $course->id,
                            'section_id' => $section->id,
                            'meta' => ['description' => $data['name']],
                            'extra' => ['featured' => false],
                        ]
                    );
                }
            }
        }
    }
}
