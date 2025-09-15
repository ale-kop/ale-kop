<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->nullable()->constrained(table: 'courses')->nullOnDelete();
            $table->foreignId('tag_id')->nullable()->constrained(table: 'tags')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained(table: 'sections')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained(table: 'users')->nullOnDelete();

            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->longText('content')->nullable();

            $table->json('meta')->nullable()->comment('Title, description, etc');
            $table->json('extra')->nullable()->comment('{"field":"...","field2":"..."}');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
