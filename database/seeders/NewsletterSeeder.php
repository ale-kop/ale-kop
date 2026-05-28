<?php

namespace Database\Seeders;

use App\Models\NewsletterList;
use Illuminate\Database\Seeder;

class NewsletterSeeder extends Seeder
{
    public function run(): void
    {
        NewsletterList::firstOrCreate(
            ['slug' => 'newsletter'],
            ['name' => 'Newsletter', 'description' => 'Lista principal de inscritos da newsletter.'],
        );
    }
}
