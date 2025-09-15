<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $defs = [
            ['name' => 'Laravel', 'featured' => true],
            ['name' => 'PHP', 'featured' => true],
            ['name' => 'Tailwind', 'featured' => true, 'custom_url' => null],
            ['name' => 'Arquitetura', 'featured' => false],
        ];

        $tags = collect();
        foreach ($defs as $data) {
            $tags->push(
                Tag::updateOrCreate(
                    ['slug' => Str::slug($data['name'])],
                    [
                        'name' => $data['name'],
                        'slug' => Str::slug($data['name']),
                        'meta' => ['description' => $data['name'].' — conteúdos relacionados'],
                        'extra' => [
                            'featured' => (bool) ($data['featured'] ?? false),
                            'custom_url' => $data['custom_url'] ?? null,
                        ],
                    ]
                )
            );
        }

        // Atribuir tags aos posts existentes conforme o curso
        Post::with('course')->get()->each(function (Post $post) use ($tags) {
            $courseSlug = $post->course?->slug ?? '';
            $tagSlug = match (true) {
                str_contains($courseSlug, 'tailwind') => 'tailwind',
                str_contains($courseSlug, 'laravel') => 'laravel',
                default => 'arquitetura',
            };

            $tag = $tags->firstWhere('slug', $tagSlug) ?? Tag::where('slug', $tagSlug)->first();
            if ($tag && ! $post->tag_id) {
                $post->update(['tag_id' => $tag->id]);
            }
        });
    }
}
