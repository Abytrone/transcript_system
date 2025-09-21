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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('score')->nullable(); // Raw exam score (0-100)
            $table->string('grade')->nullable(); // Letter grade (A, B+, B, etc.)
            $table->decimal('gpa', 3, 2)->nullable(); // GPA points (0.00-4.00)
            $table->boolean('is_resit')->default(false);
            $table->string('academic_year')->nullable(); // e.g., 2020/2021
            $table->integer('semester')->nullable(); // 1 or 2
            $table->timestamps();

            // Ensure a student can't have duplicate results for the same course in the same period
            $table->unique(['student_id', 'course_id', 'academic_year', 'semester'], 'unique_student_course_period_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
