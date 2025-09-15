<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['name' => 'Laravel Básico', 'meta' => ['description' => 'Fundamentos do Laravel']],
            ['name' => 'Laravel Avançado', 'meta' => ['description' => 'Tópicos avançados e boas práticas']],
            ['name' => 'Frontend com Tailwind', 'meta' => ['description' => 'Construindo UIs com Tailwind CSS 4']],
        ];

        foreach ($courses as $data) {
            Course::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'meta' => $data['meta'] ?? [],
                    'extra' => $data['extra'] ?? [],
                ]
            );
        }
    }
}
