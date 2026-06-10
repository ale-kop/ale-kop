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
            ['name' => 'Propostas Comerciais', 'description' => 'A Proposta Comercial mostra como você resolve o problema do cliente. Veja como estruturar propostas que fecham negócio: diagnóstico da dor, resultados mensuráveis, divisão em fases e diferenciação clara. Sem template genérico.', 'featured' => true],
            ['name' => 'Comunicação Profissional',  'description' => 'Comunicar bem é a base de tudo. Posts sobre comunicação clara e objetiva com clientes, colegas e parceiros (escrita e falada). Ser entendido sem depender da interpretação é a chave da Comunicação Eficaz.', 'featured' => true],
            ['name' => 'Vendas Consultivas',  'description' => 'Vendas consultivas de médio e alto ticket para quem domina a técnica mas perde negócio por falta de processo. Posts sobre prospecção, reuniões, follow-up, negociação e fechamento: focando sempre na comunicação.', 'featured' => false, 'custom_url' => null],
            ['name' => 'Organização e Produtividade',  'description' => 'Como organizar o processo comercial e o trabalho remoto na prática. Posts sobre produtividade para freelancers e pequenas equipes: menos reunião, mais assíncrono, processos que ajudam a vender.', 'featured' => true],
        ];

        $tags = collect();
        foreach ($defs as $data) {
            $tags->push(
                Tag::updateOrCreate(
                    ['slug' => Str::slug($data['name'])],
                    [
                        'name' => $data['name'],
                        'slug' => Str::slug($data['name']),
                        'meta' => ['description' => $data['description']],
                        'extra' => [
                            'featured' => (bool) ($data['featured'] ?? false),
                            'custom_url' => $data['custom_url'] ?? null,
                        ],
                    ]
                )
            );
        }
    }
}
