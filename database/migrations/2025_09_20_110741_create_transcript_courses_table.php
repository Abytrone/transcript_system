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
        Schema::create('transcript_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transcript_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('course_code');
            $table->string('course_name');
            $table->integer('credit_hours');
            $table->string('grade');
            $table->decimal('grade_point', 3, 2);
            $table->integer('academic_year');
            $table->string('semester');
            $table->timestamps();

            $table->unique(['transcript_id', 'course_id']);
            $table->index(['transcript_id', 'academic_year', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcript_courses');
    }
};
