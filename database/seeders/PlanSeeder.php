<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::query()->updateOrCreate(
            ['name' => 'Todos os cursos'],
            [
                'name' => 'Todos os cursos',
                'description' => 'Acesso a todos os cursos, atuais e futuros, por 1 ano.',
                'price' => 470.00,
                'interval' => 'year',
                'scope' => 'full',
                'sort_order' => 1,
            ]
        );
    }
}
