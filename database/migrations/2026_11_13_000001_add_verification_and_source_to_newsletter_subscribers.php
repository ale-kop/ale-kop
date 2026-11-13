<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // O enum de status (check constraint no Postgres) não aceita "pending".
        // Vira string curta; os valores válidos são garantidos no código.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE newsletter_subscribers DROP CONSTRAINT IF EXISTS newsletter_subscribers_status_check');
        }

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->change();

            // Dupla verificação por código de 6 dígitos
            $table->timestamp('email_verified_at')->nullable()->after('status');
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_expires_at')->nullable();
            $table->timestamp('verification_sent_at')->nullable();
            $table->unsignedTinyInteger('verification_attempts')->default(0);

            // Origem do cadastro (página onde o formulário foi enviado)
            $table->string('source_path', 255)->nullable()->index();
            $table->string('source_form', 40)->nullable();
            $table->foreignId('source_post_id')->nullable()->constrained('posts')->nullOnDelete();
        });

        $this->backfillSourcePath();
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('source_post_id');
            $table->dropIndex(['source_path']);
            $table->dropColumn([
                'email_verified_at', 'verification_code', 'verification_expires_at',
                'verification_sent_at', 'verification_attempts', 'source_path', 'source_form',
            ]);
        });
        // O status permanece string: reverter para enum falharia com linhas "pending".
    }

    /** Inscritos antigos guardavam a URL só em metadata.source_url. */
    private function backfillSourcePath(): void
    {
        DB::table('newsletter_subscribers')->whereNotNull('metadata')->orderBy('id')->each(function ($row) {
            $url = json_decode($row->metadata, true)['source_url'] ?? null;
            $path = is_string($url) ? parse_url($url, PHP_URL_PATH) : null;

            if ($path !== null && $path !== false) {
                DB::table('newsletter_subscribers')->where('id', $row->id)
                    ->update(['source_path' => mb_substr('/'.ltrim($path, '/'), 0, 255)]);
            }
        });
    }
};
