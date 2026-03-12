<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        $this->call([
            CourseSeeder::class,
            SectionSeeder::class,
            PostSeeder::class,
            TagSeeder::class,
            ReadPostsSeeder::class,
        ]);
    }
}
