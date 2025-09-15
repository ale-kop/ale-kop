<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sectionsByCourse = [
            'laravel-basico' => ['Introdução', 'Rotas e Controllers', 'Eloquent Básico'],
            'laravel-avancado' => ['Arquitetura', 'Jobs e Filas', 'Testes'],
            'frontend-com-tailwind' => ['Fundamentos', 'Layouts', 'Componentização'],
        ];

        foreach ($sectionsByCourse as $courseSlug => $sections) {
            $course = Course::where('slug', $courseSlug)->first();
            if (! $course) {
                continue;
            }
            foreach ($sections as $name) {
                Section::updateOrCreate(
                    ['course_id' => $course->id, 'name' => $name],
                    []
                );
            }
        }
    }
}
