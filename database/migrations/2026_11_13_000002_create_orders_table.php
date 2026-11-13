<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Identificador público (URL do pedido e external_reference no Mercado Pago).
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->constrained();

            $table->string('name');
            $table->string('email')->index();
            $table->string('document', 14)->nullable(); // CPF, só dígitos

            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 20); // card | pix
            $table->unsignedTinyInteger('installments')->default(1);
            $table->string('status', 20)->default('pending')->index(); // pending | approved | rejected | cancelled | refunded

            $table->string('mp_payment_id')->nullable()->unique();
            $table->string('mp_status')->nullable();
            $table->string('mp_status_detail')->nullable();

            $table->text('pix_qr_code')->nullable();
            $table->longText('pix_qr_code_base64')->nullable();
            $table->timestamp('pix_expires_at')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
