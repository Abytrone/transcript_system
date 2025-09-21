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
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('transcript_number')->unique();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('program');
            $table->integer('year_of_completion');
            $table->decimal('cgpa', 3, 2);
            $table->string('class_of_degree');
            $table->text('qr_code')->nullable();
            $table->enum('status', ['draft', 'issued', 'verified'])->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'status']);
            $table->index('transcript_number');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
