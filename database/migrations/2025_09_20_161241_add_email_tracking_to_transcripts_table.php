<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->string('recipient_email')->nullable()->after('issued_by');
            $table->timestamp('email_sent_at')->nullable()->after('recipient_email');
            $table->enum('delivery_method', ['pickup', 'email', 'mail'])->default('pickup')->after('email_sent_at');
            $table->text('delivery_notes')->nullable()->after('delivery_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transcripts', function (Blueprint $table) {
            $table->dropColumn(['recipient_email', 'email_sent_at', 'delivery_method', 'delivery_notes']);
        });
    }
};
