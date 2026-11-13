<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

/**
 * Cria (ou atualiza) o curso vendido pela página /orcamentos-pelo-whatsapp.
 * php artisan db:seed --class=WhatsappOfferCourseSeeder
 * O preço é um placeholder: ajuste no admin (Cursos → editar).
 */
class WhatsappOfferCourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::query()->firstOrNew(['slug' => config('offers.whatsapp.course_slug')]);

        $course->fill([
            'name' => 'Orçamentos e propostas comerciais pelo WhatsApp',
            'meta' => array_merge($course->meta ?? [], [
                'description' => 'Mande uma mensagem para o seu WhatsApp e receba o orçamento ou a proposta pronta para enviar ao cliente.',
            ]),
            'extra' => array_merge(['price' => 197.00], $course->extra ?? [], [
                'access' => 'paid',
                'custom_url' => route('offers.whatsapp', absolute: false),
            ]),
        ])->save();
    }
}
