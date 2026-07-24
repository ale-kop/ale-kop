<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('scope')->default('course');
            $table->string('status')->default('active');
            $table->string('source')->default('manual');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'scope', 'status']);
            $table->index(['user_id', 'course_id', 'status']);
        });

        DB::statement("CREATE UNIQUE INDEX course_accesses_unique_full_access ON course_accesses (user_id) WHERE scope = 'full' AND course_id IS NULL");
        DB::statement("CREATE UNIQUE INDEX course_accesses_unique_course_access ON course_accesses (user_id, course_id) WHERE scope = 'course' AND course_id IS NOT NULL");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS course_accesses_unique_course_access');
        DB::statement('DROP INDEX IF EXISTS course_accesses_unique_full_access');

        Schema::dropIfExists('course_accesses');
    }
};
