<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_subscriber', function (Blueprint $table) {
            $table->foreignId('list_id')->constrained('newsletter_lists')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->constrained('newsletter_subscribers')->cascadeOnDelete();
            $table->primary(['list_id', 'subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_subscriber');
    }
};
